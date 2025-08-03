<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Log;
use App\Models\Goods;
use App\Models\Order;
use App\Models\retur;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $penjualan, $pemasukan, $debt, $expense, $customers, $suppliers, $employees, $goods;

    public $selectedWeek, $selectedMonth, $startDate, $endDate;

    public function getWeekOptionsForDropdown($month)
    {
        $year = Carbon::now()->year;
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $weeks = [];
        $currentStart = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);

        while ($currentStart->lte($endOfMonth)) {
            $currentEnd = $currentStart->copy()->endOfWeek(Carbon::SUNDAY);

            $weekStart = $currentStart->copy()->lt($startOfMonth) ? $startOfMonth->copy() : $currentStart->copy();
            $weekEnd = $currentEnd->copy()->gt($endOfMonth) ? $endOfMonth->copy() : $currentEnd->copy();

            $weeks[] = [$weekStart->startOfDay(), $weekEnd->endOfDay()];
            $currentStart->addWeek();
        }

        $labels = [];
        foreach ($weeks as $i => [$start, $end]) {
            $labels[] = [
                'value' => $i + 1,
                'label' => 'Pekan ' . ($i + 1) . ' '
            ];
        }

        return $labels;
    }

    public function getCurrentCalendarWeekNumber($month)
    {
        $year = Carbon::now()->year;
        $today = Carbon::now();

        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $weeks = [];
        $currentStart = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);

        while ($currentStart->lte($endOfMonth)) {
            $currentEnd = $currentStart->copy()->endOfWeek(Carbon::SUNDAY);

            $weekStart = $currentStart->lt($startOfMonth) ? $startOfMonth->copy() : $currentStart->copy();
            $weekEnd = $currentEnd->gt($endOfMonth) ? $endOfMonth->copy() : $currentEnd->copy();

            $weeks[] = [$weekStart->startOfDay(), $weekEnd->endOfDay()];
            $currentStart->addWeek();
        }

        foreach ($weeks as $i => [$start, $end]) {
            if ($today->between($start, $end)) {
                return $i + 1;
            }
        }

        return 1; // fallback default
    }
    public function mount()
    {
        $this->customers = Customer::all();
        $this->suppliers = Supplier::all();
        $this->employees = Employee::all();
        $this->goods = Goods::all();

        $this->selectedMonth = Carbon::now()->month;
        $this->selectedWeek = $this->getCurrentCalendarWeekNumber($this->selectedMonth);
    }

    public function render()
    {
        $data = $this->getChartData();
        $donutChartData = $this->getDonutChartData();

        return view('livewire.dashboard', compact('data', 'donutChartData'));
    }

    public function getChartData()
    {
        $selectedMonth = is_numeric($this->selectedMonth) && $this->selectedMonth >= 1 && $this->selectedMonth <= 12
            ? (int) $this->selectedMonth
            : Carbon::now()->month;

        // Fix fallback ke pekan 1, bukan ke pekan sekarang
        $selectedWeek = is_numeric($this->selectedWeek) && $this->selectedWeek >= 1
            ? (int) $this->selectedWeek
            : 1;

        [$startOfWeek, $endOfWeek] = $this->getCalendarStyleWeekDateRange($selectedMonth, $selectedWeek);

        $incomes = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->pluck('total', 'date');

        $expenses = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->pluck('total', 'date');

        $labels = [];
        $incomeData = [];
        $expenseData = [];

        $period = Carbon::parse($startOfWeek)->toPeriod($endOfWeek);

        foreach ($period as $date) {
            if ($date->month == $selectedMonth) {
                $labels[] = $date->translatedFormat('D d');
                $incomeData[] = $incomes->get($date->toDateString(), 0);
                $expenseData[] = $expenses->get($date->toDateString(), 0);
            }
        }

        $this->penjualan = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total');
        $this->pemasukan = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('grand_total');
        $this->debt = Transaction::where('status', 'hutang')->whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum(DB::raw('grand_total - bill'));
        $this->expense = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total');

        return [
            'labels' => $labels,
            'incomeData' => $incomeData,
            'expenseData' => $expenseData,
        ];
    }

    private function getCalendarStyleWeekDateRange($month, $week)
    {
        $year = Carbon::now()->year;
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $weeks = [];

        // Mulai dari hari Senin sebelum atau sama dengan tanggal 1
        $currentStart = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);

        while ($currentStart->lte($endOfMonth)) {
            $currentEnd = $currentStart->copy()->endOfWeek(Carbon::SUNDAY);

            // Potong agar tidak lewat dari akhir bulan
            $weekStart = $currentStart->copy()->lt($startOfMonth) ? $startOfMonth->copy() : $currentStart->copy();
            $weekEnd = $currentEnd->copy()->gt($endOfMonth) ? $endOfMonth->copy() : $currentEnd->copy();

            $weeks[] = [$weekStart->startOfDay(), $weekEnd->endOfDay()];
            $currentStart->addWeek(); // lanjut ke minggu berikutnya
        }

        // Ambil minggu ke-n
        if (!isset($weeks[$week - 1])) {
            // Jika tidak ada, fallback ke akhir bulan
            return [$endOfMonth->startOfDay(), $endOfMonth->endOfDay()];
        }

        return $weeks[$week - 1];
    }


    public function getDonutChartData()
    {
        $selectedMonth = is_numeric($this->selectedMonth) && $this->selectedMonth >= 1 && $this->selectedMonth <= 12
            ? (int) $this->selectedMonth
            : Carbon::now()->month; // default ke bulan sekarang

        // Fix: validasi pekan (>=1) dan fallback ke pekan pertama, bukan ke pekan sekarang
        $selectedWeek = is_numeric($this->selectedWeek) && $this->selectedWeek >= 1
            ? (int) $this->selectedWeek
            : 1;

        // Ambil range tanggal sesuai pekan yang dipilih
        [$startOfWeek, $endOfWeek] = $this->getCalendarStyleWeekDateRange($selectedMonth, $selectedWeek);

        // Query transaksi pekan yang dipilih
        $salesData = Transaction::where('status', '!=', 'hutang')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->with('goods.category')
            ->has('goods')
            ->get();

        // Hitung kategori dan qty
        $categories = [];
        foreach ($salesData as $transaction) {
            foreach ($transaction->goods as $good) {
                $categoryName = $good->category->name;
                if (!isset($categories[$categoryName])) {
                    $categories[$categoryName] = 0;
                }
                $categories[$categoryName] += $good->pivot->qty;
            }
        }

        // Jika tidak ada transaksi, chart harus kosong
        if (empty($categories)) {
            return [
                'labels' => [],
                'data' => [],
            ];
        }

        return [
            'labels' => array_keys($categories),
            'data' => array_values($categories),
        ];
    }
    public function updatedSelectedMonth()
    {
        // Validasi nilai bulan agar selalu antara 1–12
        $this->selectedMonth = is_numeric($this->selectedMonth) && $this->selectedMonth >= 1 && $this->selectedMonth <= 12
            ? (int) $this->selectedMonth
            : Carbon::now()->month;

        // Reset pekan ke-1 karena bulan telah berubah
        $this->selectedWeek = 1;

        $this->refreshCharts();
    }

    public function updatedSelectedWeek()
    {
        // Pastikan nilai pekan valid secara numerik
        $this->selectedWeek = is_numeric($this->selectedWeek) ? (int) $this->selectedWeek : 1;

        // Ambil jumlah pekan aktual berdasarkan kalender bulan ini
        $weeks = $this->getWeekOptionsForDropdown($this->selectedMonth);
        $totalWeeks = count($weeks);

        // Validasi agar pekan tidak melebihi jumlah pekan aktual
        if ($this->selectedWeek < 1 || $this->selectedWeek > $totalWeeks) {
            $this->selectedWeek = 1;
        }

        $this->refreshCharts();
    }

    private function refreshCharts()
    {
        $data = $this->getChartData();
        $donut = $this->getDonutChartData();

        // Emit event ke frontend untuk memperbarui chart
        $this->emit('refreshChart', $data, $donut);
    }
}
