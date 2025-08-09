<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Goods;
use App\Models\GoodsTransaction;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Chart extends Component
{
    // Public properties
    public $search = '';
    public $searchResults = [];
    public $selectedGoodsId = null;
    public $selectedGoods = null;
    public $activePeriod = '1M';
    public $chartData = ['labels' => [], 'datasets' => []];
    public $summaryStatements = [];
    public $showSearchModal = false;
    
    // Filter properties
    public $showFilterModal = false;
    public $selectedCategoryId = null;
    public $selectedBrandId = null;
    public $categories = [];
    public $brands = [];

    public function mount()
    {
        $this->updateChartAndSummary();
    }

    public function updatedSearch()
    {
        $this->loadFilteredGoods();
    }

    public function openSearchModal()
    {
        $this->showSearchModal = true;
        $this->search = '';
        $this->loadFilterData();
        $this->loadFilteredGoods();
    }

    public function closeSearchModal()
    {
        $this->showSearchModal = false;
        $this->search = '';
        $this->searchResults = [];
    }

    public function selectGood($goodsId)
    {
        $good = Goods::find($goodsId);
        if ($good) {
            $this->selectedGoodsId = $goodsId;
            $this->selectedGoods = [
                'id' => $good->id,
                'name' => $good->name
            ];
        }

        $this->closeSearchModal();
        $this->updateChartAndSummary();
    }

    public function clearSelection()
    {
        $this->selectedGoodsId = null;
        $this->selectedGoods = null;
        $this->updateChartAndSummary();
    }

    public function setActivePeriod($period)
    {
        $this->activePeriod = $period;
        $this->updateChartAndSummary();
    }
    
    public function clearFilters()
    {
        $this->selectedCategoryId = null;
        $this->selectedBrandId = null;
        $this->loadFilteredGoods();
    }
    
    protected function loadFilterData()
    {
        $this->categories = Category::orderBy('name')->get(['id', 'name'])->toArray();
        $this->brands = Brand::where('is_active', true)->orderBy('name')->get(['id', 'name'])->toArray();
    }
    
    protected function loadFilteredGoods()
    {
        $query = Goods::query();
        
        if ($this->selectedCategoryId) {
            $query->where('category_id', $this->selectedCategoryId);
        }
        
        if ($this->selectedBrandId) {
            $query->where('brand_id', $this->selectedBrandId);
        }
        
        if (strlen($this->search) > 0) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        
        $this->searchResults = $query->orderBy('name')
            ->limit(20)
            ->get(['id', 'name'])
            ->toArray();
    }
    
    public function updatedSelectedCategoryId()
    {
        $this->loadFilteredGoods();
    }
    
    public function updatedSelectedBrandId()
    {
        $this->loadFilteredGoods();
    }

    protected function updateChartAndSummary()
    {
        $this->calculateMovingAverageData();
        $this->generateSummaryStatements();

        // Emit event to update the chart with force refresh
        $this->dispatchBrowserEvent('chartDataUpdated', $this->chartData);
        $this->emit('updateMovingAverageChart', $this->chartData);
    }
    
    public function refreshChart()
    {
        $this->updateChartAndSummary();
    }

    protected function calculateMovingAverageData()
    {
        $this->chartData = ['labels' => [], 'datasets' => []];

        if (empty($this->selectedGoodsId)) {
            return;
        }

        $startDate = $this->getStartDateForPeriod($this->activePeriod);
        $endDate = Carbon::now();
        
        // Debug logging
        \Log::info('Chart calculation started', [
            'selected_goods_id' => $this->selectedGoodsId,
            'active_period' => $this->activePeriod,
            'start_date' => $startDate->format('Y-m-d H:i:s'),
            'end_date' => $endDate->format('Y-m-d H:i:s')
        ]);

        // Generate date series based on period with appropriate intervals
        $dates = $this->generateDateSeries($startDate, $endDate, $this->activePeriod);
        
        \Log::info('Generated dates', ['dates_count' => count($dates), 'period' => $this->activePeriod]);

        // Format dates for better readability on chart based on period
        $this->chartData['labels'] = $this->formatLabelsForPeriod($dates, $this->activePeriod);

        $good = Goods::find($this->selectedGoodsId);
        if (!$good) return;

        $quantities = [];

        foreach ($dates as $dateInfo) {
            // Get total quantity sold for this good in this period
            $totalQty = $this->getQuantityForPeriod($dateInfo, $this->activePeriod);
            $quantities[] = $totalQty ? (float) $totalQty : 0;
        }
        
        \Log::info('Quantities calculated', [
            'goods_id' => $this->selectedGoodsId,
            'goods_name' => $good->name,
            'quantities' => array_slice($quantities, 0, 10),
            'total_periods' => count($quantities)
        ]);

        // Calculate Moving Average based on period
        $smaData = $this->calculateMovingAverageForPeriod($quantities, $this->activePeriod);

        $movingAveragePeriod = $this->getMovingAveragePeriodName($this->activePeriod);
        
        $this->chartData['datasets'][] = [
            'label' => $good->name . ' - Quantity Sold (' . $movingAveragePeriod . ')',
            'data' => $smaData,
            'borderColor' => 'rgb(54, 162, 235)',
            'backgroundColor' => 'rgba(54, 162, 235, 0.1)',
            'fill' => false,
            'tension' => 0.1,
            'pointRadius' => 2,
            'pointHoverRadius' => 4
        ];
        
        \Log::info('Chart data prepared', [
            'labels_count' => count($this->chartData['labels']),
            'datasets_count' => count($this->chartData['datasets']),
            'data_points' => count($this->chartData['datasets'][0]['data'] ?? [])
        ]);
    }

    protected function generateSummaryStatements()
    {
        $this->summaryStatements = [];

        if (empty($this->selectedGoods)) {
            return;
        }

        $goodsId = $this->selectedGoods['id'];
        $goodsName = $this->selectedGoods['name'];

        // Generate analysis for current active period and others
        $periods = ['5D', '1M', '3M', '6M', '1Y', '5Y', 'All'];

        foreach ($periods as $period) {
            $analysis = $this->generateDetailedAnalysisForPeriod($goodsId, $goodsName, $period);
            if ($analysis) {
                $this->summaryStatements[] = $analysis;
            }
        }
    }

    protected function generateDetailedAnalysisForPeriod($goodsId, $goodsName, $period)
    {
        $startDate = $this->getStartDateForPeriod($period);
        $endDate = Carbon::now();
        
        // Calculate total quantity, average, and trend for the period
        $totalQty = GoodsTransaction::join('transactions', 'goods_transaction.transaction_id', '=', 'transactions.id')
            ->where('goods_transaction.goods_id', $goodsId)
            ->where('transactions.created_at', '>=', $startDate)
            ->where('transactions.created_at', '<=', $endDate)
            ->sum('goods_transaction.qty');
            
        $avgQty = GoodsTransaction::join('transactions', 'goods_transaction.transaction_id', '=', 'transactions.id')
            ->where('goods_transaction.goods_id', $goodsId)
            ->where('transactions.created_at', '>=', $startDate)
            ->where('transactions.created_at', '<=', $endDate)
            ->avg('goods_transaction.qty');
            
        $transactionCount = GoodsTransaction::join('transactions', 'goods_transaction.transaction_id', '=', 'transactions.id')
            ->where('goods_transaction.goods_id', $goodsId)
            ->where('transactions.created_at', '>=', $startDate)
            ->where('transactions.created_at', '<=', $endDate)
            ->count();

        if (!$totalQty || !$avgQty) {
            return [
                'goods_name' => $goodsName,
                'period' => $period,
                'is_active' => $period === $this->activePeriod,
                'statement' => sprintf(
                    'Tidak ada penjualan %s dalam periode %s.',
                    $goodsName,
                    $this->getPeriodDisplayName($period)
                ),
                'details' => [
                    'total_qty' => 0,
                    'avg_qty' => 0,
                    'transaction_count' => 0,
                    'trend' => 'no_data'
                ]
            ];
        }

        // Calculate trend by comparing first and second half of the period
        $midDate = $startDate->copy()->addDays($startDate->diffInDays($endDate) / 2);
        
        $firstHalfQty = GoodsTransaction::join('transactions', 'goods_transaction.transaction_id', '=', 'transactions.id')
            ->where('goods_transaction.goods_id', $goodsId)
            ->where('transactions.created_at', '>=', $startDate)
            ->where('transactions.created_at', '<=', $midDate)
            ->avg('goods_transaction.qty');
            
        $secondHalfQty = GoodsTransaction::join('transactions', 'goods_transaction.transaction_id', '=', 'transactions.id')
            ->where('goods_transaction.goods_id', $goodsId)
            ->where('transactions.created_at', '>=', $midDate)
            ->where('transactions.created_at', '<=', $endDate)
            ->avg('goods_transaction.qty');

        $trend = 'stabil';
        $trendPercentage = 0;
        
        if ($firstHalfQty && $secondHalfQty) {
            $trendPercentage = (($secondHalfQty - $firstHalfQty) / $firstHalfQty) * 100;
            
            if ($trendPercentage > 10) {
                $trend = 'naik signifikan';
            } elseif ($trendPercentage > 5) {
                $trend = 'naik';
            } elseif ($trendPercentage < -10) {
                $trend = 'turun signifikan';
            } elseif ($trendPercentage < -5) {
                $trend = 'turun';
            }
        }

        // Generate comprehensive statement
        $statement = sprintf(
            'Periode %s: Total penjualan %s adalah %.0f unit dalam %d transaksi (rata-rata %.1f unit/transaksi). Trend %s dengan perubahan %.1f%%.',
            $this->getPeriodDisplayName($period),
            $goodsName,
            $totalQty,
            $transactionCount,
            $avgQty,
            $trend,
            abs($trendPercentage)
        );

        return [
            'goods_name' => $goodsName,
            'period' => $period,
            'is_active' => $period === $this->activePeriod,
            'statement' => $statement,
            'details' => [
                'total_qty' => round($totalQty),
                'avg_qty' => round($avgQty, 2),
                'transaction_count' => $transactionCount,
                'trend' => $trend,
                'trend_percentage' => round($trendPercentage, 1)
            ]
        ];
    }

    public function getStartDateForPeriod($period)
    {
        switch ($period) {
            case '5D':
                return Carbon::now()->subDays(5);
            case '1M':
                return Carbon::now()->subMonth();
            case '3M':
                return Carbon::now()->subMonths(3);
            case '6M':
                return Carbon::now()->subMonths(6);
            case '1Y':
                return Carbon::now()->subYear();
            case '5Y':
                return Carbon::now()->subYears(5);
            case 'All':
                return Carbon::create(1970, 1, 1); // Very old date
            default:
                return Carbon::now()->subMonth();
        }
    }

    protected function generateDateSeries($startDate, $endDate, $period)
    {
        $dates = [];
        $currentDate = $startDate->copy();
        
        switch ($period) {
            case '5D':
                // Daily intervals
                while ($currentDate <= $endDate) {
                    $dates[] = [
                        'start' => $currentDate->copy()->startOfDay(),
                        'end' => $currentDate->copy()->endOfDay(),
                        'label_date' => $currentDate->copy()
                    ];
                    $currentDate->addDay();
                }
                break;
                
            case '1M':
                // Daily intervals for 1 month
                while ($currentDate <= $endDate) {
                    $dates[] = [
                        'start' => $currentDate->copy()->startOfDay(),
                        'end' => $currentDate->copy()->endOfDay(),
                        'label_date' => $currentDate->copy()
                    ];
                    $currentDate->addDay();
                }
                break;
                
            case '3M':
                // Weekly intervals for 3 months
                $currentDate = $currentDate->startOfWeek();
                while ($currentDate <= $endDate) {
                    $weekEnd = $currentDate->copy()->endOfWeek();
                    if ($weekEnd > $endDate) $weekEnd = $endDate->copy();
                    
                    $dates[] = [
                        'start' => $currentDate->copy(),
                        'end' => $weekEnd,
                        'label_date' => $currentDate->copy()
                    ];
                    $currentDate->addWeek();
                }
                break;
                
            case '6M':
                // Weekly intervals for 6 months
                $currentDate = $currentDate->startOfWeek();
                while ($currentDate <= $endDate) {
                    $weekEnd = $currentDate->copy()->endOfWeek();
                    if ($weekEnd > $endDate) $weekEnd = $endDate->copy();
                    
                    $dates[] = [
                        'start' => $currentDate->copy(),
                        'end' => $weekEnd,
                        'label_date' => $currentDate->copy()
                    ];
                    $currentDate->addWeek();
                }
                break;
                
            case '1Y':
                // Monthly intervals for 1 year
                $currentDate = $currentDate->startOfMonth();
                while ($currentDate <= $endDate) {
                    $monthEnd = $currentDate->copy()->endOfMonth();
                    if ($monthEnd > $endDate) $monthEnd = $endDate->copy();
                    
                    $dates[] = [
                        'start' => $currentDate->copy(),
                        'end' => $monthEnd,
                        'label_date' => $currentDate->copy()
                    ];
                    $currentDate->addMonth();
                }
                break;
                
            case '5Y':
                // Monthly intervals for 5 years
                $currentDate = $currentDate->startOfMonth();
                while ($currentDate <= $endDate) {
                    $monthEnd = $currentDate->copy()->endOfMonth();
                    if ($monthEnd > $endDate) $monthEnd = $endDate->copy();
                    
                    $dates[] = [
                        'start' => $currentDate->copy(),
                        'end' => $monthEnd,
                        'label_date' => $currentDate->copy()
                    ];
                    $currentDate->addMonth();
                }
                break;
                
            case 'All':
                // Quarterly intervals for all time
                $currentDate = $currentDate->startOfQuarter();
                while ($currentDate <= $endDate) {
                    $quarterEnd = $currentDate->copy()->endOfQuarter();
                    if ($quarterEnd > $endDate) $quarterEnd = $endDate->copy();
                    
                    $dates[] = [
                        'start' => $currentDate->copy(),
                        'end' => $quarterEnd,
                        'label_date' => $currentDate->copy()
                    ];
                    $currentDate->addQuarter();
                }
                break;
                
            default:
                // Daily intervals as fallback
                while ($currentDate <= $endDate) {
                    $dates[] = [
                        'start' => $currentDate->copy()->startOfDay(),
                        'end' => $currentDate->copy()->endOfDay(),
                        'label_date' => $currentDate->copy()
                    ];
                    $currentDate->addDay();
                }
        }
        
        return $dates;
    }

    protected function formatLabelsForPeriod($dates, $period)
    {
        $labels = [];
        
        foreach ($dates as $dateInfo) {
            $date = $dateInfo['label_date'];
            
            switch ($period) {
                case '5D':
                    $labels[] = $date->format('D, M j'); // Mon, Aug 5
                    break;
                    
                case '1M':
                    $labels[] = $date->format('M j'); // Aug 5
                    break;
                    
                case '3M':
                case '6M':
                    $labels[] = 'Week ' . $date->weekOfYear . ', ' . $date->format('Y'); // Week 32, 2024
                    break;
                    
                case '1Y':
                    $labels[] = $date->format('M Y'); // Aug 2024
                    break;
                    
                case '5Y':
                    $labels[] = $date->format('M y'); // Aug 24
                    break;
                    
                case 'All':
                    $labels[] = $date->format('Q\QY'); // Q3 2024
                    break;
                    
                default:
                    $labels[] = $date->format('M j');
            }
        }
        
        return $labels;
    }

    protected function getQuantityForPeriod($dateInfo, $period)
    {
        return GoodsTransaction::join('transactions', 'goods_transaction.transaction_id', '=', 'transactions.id')
            ->where('goods_transaction.goods_id', $this->selectedGoodsId)
            ->where('transactions.created_at', '>=', $dateInfo['start'])
            ->where('transactions.created_at', '<=', $dateInfo['end'])
            ->sum('goods_transaction.qty');
    }

    protected function calculateMovingAverageForPeriod($quantities, $period)
    {
        $windowSize = $this->getMovingAverageWindow($period);
        $smaData = [];
        
        foreach ($quantities as $index => $quantity) {
            $startIndex = max(0, $index - $windowSize + 1);
            $quantitiesForSMA = array_slice($quantities, $startIndex, $index - $startIndex + 1);

            if (count($quantitiesForSMA) > 0) {
                $smaData[] = array_sum($quantitiesForSMA) / count($quantitiesForSMA);
            } else {
                $smaData[] = 0;
            }
        }
        
        return $smaData;
    }

    protected function getMovingAverageWindow($period)
    {
        switch ($period) {
            case '5D':
                return 3; // 3-day SMA
            case '1M':
                return 7; // 7-day SMA
            case '3M':
            case '6M':
                return 4; // 4-week SMA
            case '1Y':
            case '5Y':
                return 3; // 3-month SMA
            case 'All':
                return 4; // 4-quarter SMA
            default:
                return 7;
        }
    }

    protected function getMovingAveragePeriodName($period)
    {
        switch ($period) {
            case '5D':
                return '3-day SMA';
            case '1M':
                return '7-day SMA';
            case '3M':
            case '6M':
                return '4-week SMA';
            case '1Y':
            case '5Y':
                return '3-month SMA';
            case 'All':
                return '4-quarter SMA';
            default:
                return '7-day SMA';
        }
    }

    protected function getPeriodDisplayName($period)
    {
        switch ($period) {
            case '5D':
                return '5 hari terakhir';
            case '1M':
                return '1 bulan terakhir';
            case '3M':
                return '3 bulan terakhir';
            case '6M':
                return '6 bulan terakhir';
            case '1Y':
                return '1 tahun terakhir';
            case '5Y':
                return '5 tahun terakhir';
            case 'All':
                return 'sepanjang waktu';
            default:
                return $period;
        }
    }

    public function render()
    {
        return view('livewire.chart')
            ->layout('layouts.app', ['title' => 'Moving Average Analysis']);
    }
}
