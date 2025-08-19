<div>
    <x-slot name="title">{{ __('Tren Penjualan') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Tren Penjualan'];
        @endphp
        @foreach ($breadcumb as $item)
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <span
                    class="block transition hover:text-gray-700 @if ($loop->last) text-gray-950 font-medium @endif">
                    {{ $item }}
                </span>
            </li>
        @endforeach
    </x-slot>
    <div class="flex items-center justify-between mb-6">


        <div class="flex space-x-2">
            <!-- Product Selection Button -->
            <button wire:click="openSearchModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-2 transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span>{{ $selectedGoods ? $selectedGoods['name'] : 'Pilih Barang' }}</span>
            </button>

            @if ($selectedGoods)
                <!-- Refresh Chart Button -->
                <button wire:click="refreshChart"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ↻ Refresh
                </button>

                <!-- Clear Selection Button -->
                <button wire:click="clearSelection"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Clear
                </button>
            @endif
        </div>
    </div>

    <!-- Time Period Selection -->
    @if ($selectedGoods)
        <div class="flex items-center space-x-2 mb-4">
            <span class="text-sm text-gray-600">Time Period:</span>
            @foreach (['5D', '1M', '3M', '6M', '1Y', '5Y', 'All'] as $period)
                <button wire:click="setActivePeriod('{{ $period }}')"
                    class="px-3 py-1.5 text-sm font-medium transition-colors rounded
                           {{ $activePeriod === $period ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    {{ $period }}
                </button>
            @endforeach
        </div>
    @endif

    <!-- Chart Container -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        @if ($selectedGoods)
            <canvas id="movingAverageChart" class="w-full h-[400px]" wire:ignore></canvas>

            <!-- Script to refresh chart when component updates -->
            <script>
                document.addEventListener('livewire:update', function() {
                    setTimeout(() => {
                        const chartData = @this.chartData;
                        if (chartData && chartData.datasets && chartData.datasets.length > 0) {
                            console.log('Livewire updated, refreshing chart with:', chartData);
                            if (typeof updateChart === 'function') {
                                updateChart(chartData);
                            }
                        }
                    }, 100);
                });
            </script>
        @else
            <div class="flex flex-col items-center justify-center h-[400px] text-gray-500">
                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <h3 class="text-xl font-medium mb-2">Select a Product</h3>
                <p class="text-center mb-4">Choose a product to view its moving average chart and sales analysis</p>
                <button wire:click="openSearchModal"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors cursor-pointer">
                    Select Product
                </button>
            </div>
        @endif
    </div>

    <!-- Summary Statistics -->
    @if ($selectedGoods && count($summaryStatements) > 0)
        <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-6 text-gray-900">Analisis Penjualan Kompleks -
                {{ $selectedGoods['name'] }}</h3>

            <!-- Active Period Highlight -->
            @php
                $activeSummary = collect($summaryStatements)->firstWhere('is_active', true);
            @endphp

            @if ($activeSummary)
                <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-2">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                            Periode Aktif: {{ $activeSummary['period'] }}
                        </span>
                    </h4>
                    <p class="text-blue-800 text-sm leading-relaxed">{{ $activeSummary['statement'] }}</p>

                    @if (isset($activeSummary['details']))
                        <div class="mt-3 flex flex-wrap gap-4 text-xs">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-1"></span>
                                <span class="text-blue-700">Total:
                                    {{ number_format($activeSummary['details']['total_qty']) }} unit</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                <span class="text-blue-700">Rata-rata: {{ $activeSummary['details']['avg_qty'] }}
                                    unit/transaksi</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-purple-500 rounded-full mr-1"></span>
                                <span class="text-blue-700">Transaksi:
                                    {{ $activeSummary['details']['transaction_count'] }}</span>
                            </div>
                            <div class="flex items-center">
                                @php
                                    $trendColor = 'gray';
                                    if (str_contains($activeSummary['details']['trend'], 'naik')) {
                                        $trendColor = 'green';
                                    }
                                    if (str_contains($activeSummary['details']['trend'], 'turun')) {
                                        $trendColor = 'red';
                                    }
                                @endphp
                                <span class="w-2 h-2 bg-{{ $trendColor }}-500 rounded-full mr-1"></span>
                                <span class="text-blue-700">Trend:
                                    {{ ucfirst($activeSummary['details']['trend']) }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- All Periods Analysis -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach ($summaryStatements as $index => $statement)
                    @php
                        $isActive = $statement['is_active'] ?? false;
                        $periodColors = [
                            '5D' => 'bg-red-50 border-red-200',
                            '1M' => 'bg-orange-50 border-orange-200',
                            '3M' => 'bg-yellow-50 border-yellow-200',
                            '6M' => 'bg-green-50 border-green-200',
                            '1Y' => 'bg-blue-50 border-blue-200',
                            '5Y' => 'bg-indigo-50 border-indigo-200',
                            'All' => 'bg-purple-50 border-purple-200',
                        ];
                        $cardClass = $periodColors[$statement['period']] ?? 'bg-gray-50 border-gray-200';
                        if ($isActive) {
                            $cardClass = 'bg-blue-100 border-blue-300 ring-2 ring-blue-200';
                        }
                    @endphp

                    <div class="{{ $cardClass }} rounded-lg p-4 border transition-all hover:shadow-md">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $isActive ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $statement['period'] }}
                                </span>
                                @if ($isActive)
                                    <span class="ml-2 text-xs text-blue-600 font-medium">● AKTIF</span>
                                @endif
                            </div>

                            @if (isset($statement['details']['trend']) && $statement['details']['trend'] !== 'no_data')
                                @php
                                    $trendIcon = '→';
                                    $trendColor = 'text-gray-500';
                                    if (str_contains($statement['details']['trend'], 'naik')) {
                                        $trendIcon = '↗';
                                        $trendColor = 'text-green-600';
                                    } elseif (str_contains($statement['details']['trend'], 'turun')) {
                                        $trendIcon = '↘';
                                        $trendColor = 'text-red-600';
                                    }
                                @endphp
                                <span class="{{ $trendColor }} font-bold text-lg">{{ $trendIcon }}</span>
                            @endif
                        </div>

                        <p class="text-gray-900 text-sm leading-relaxed mb-3">{{ $statement['statement'] }}</p>

                        @if (isset($statement['details']) && $statement['details']['total_qty'] > 0)
                            <div class="border-t pt-3 mt-3">
                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <div class="text-center p-2 bg-white rounded border">
                                        <div class="font-semibold text-gray-900">
                                            {{ number_format($statement['details']['total_qty']) }}</div>
                                        <div class="text-gray-600">Total Unit</div>
                                    </div>
                                    <div class="text-center p-2 bg-white rounded border">
                                        <div class="font-semibold text-gray-900">{{ $statement['details']['avg_qty'] }}
                                        </div>
                                        <div class="text-gray-600">Avg/Transaksi</div>
                                    </div>
                                </div>

                                <div class="mt-2 flex justify-between items-center text-xs text-gray-600">
                                    <span>{{ $statement['details']['transaction_count'] }} transaksi</span>
                                    @if (isset($statement['details']['trend_percentage']))
                                        <span
                                            class="font-medium">{{ $statement['details']['trend_percentage'] > 0 ? '+' : '' }}{{ $statement['details']['trend_percentage'] }}%</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Performance Summary -->
            @php
                $bestPeriod = collect($summaryStatements)
                    ->filter(function ($s) {
                        return isset($s['details']['total_qty']) && $s['details']['total_qty'] > 0;
                    })
                    ->sortByDesc('details.total_qty')
                    ->first();

                $worstPeriod = collect($summaryStatements)
                    ->filter(function ($s) {
                        return isset($s['details']['total_qty']) && $s['details']['total_qty'] > 0;
                    })
                    ->sortBy('details.total_qty')
                    ->first();
            @endphp

            @if ($bestPeriod || $worstPeriod)
                <div class="mt-6 border-t pt-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Ringkasan Performa</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if ($bestPeriod)
                            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                <div class="flex items-center mb-2">
                                    <span class="text-green-600 mr-2">🏆</span>
                                    <span class="font-medium text-green-900">Periode Terbaik</span>
                                </div>
                                <div class="text-sm text-green-800">
                                    <strong>{{ $bestPeriod['period'] }}</strong> dengan total penjualan
                                    <strong>{{ number_format($bestPeriod['details']['total_qty']) }} unit</strong>
                                </div>
                            </div>
                        @endif

                        @if ($worstPeriod && $worstPeriod !== $bestPeriod)
                            <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                                <div class="flex items-center mb-2">
                                    <span class="text-orange-600 mr-2">📊</span>
                                    <span class="font-medium text-orange-900">Periode Terendah</span>
                                </div>
                                <div class="text-sm text-orange-800">
                                    <strong>{{ $worstPeriod['period'] }}</strong> dengan total penjualan
                                    <strong>{{ number_format($worstPeriod['details']['total_qty']) }} unit</strong>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Search Modal -->
    @if ($showSearchModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" style="z-index: 9999;">
            <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-white">Pilih Barang</h3>
                    <button wire:click="closeSearchModal" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Filters & Search Input -->
                <div class="p-4 space-y-4">
                    <!-- Filter Dropdowns -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Category Dropdown -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Kategori</label>
                            <select wire:model="selectedCategoryId"
                                class="w-full bg-gray-700 text-white px-3 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Brand Dropdown -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Brand</label>
                            <select wire:model="selectedBrandId"
                                class="w-full bg-gray-700 text-white px-3 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Semua Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand['id'] }}">{{ $brand['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Clear Filters Button -->
                    @if ($selectedCategoryId || $selectedBrandId)
                        <div class="flex justify-end">
                            <button wire:click="clearFilters"
                                class="text-gray-400 hover:text-white text-sm underline transition-colors">
                                Reset Filter
                            </button>
                        </div>
                    @endif

                    <!-- Search Input -->
                    <div class="relative">
                        <input type="text" wire:model="search" placeholder="Type to search goods..."
                            class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            autofocus>

                        <!-- Loading indicator -->
                        <div wire:loading wire:target="search,selectedCategoryId,selectedBrandId"
                            class="absolute right-3 top-3">
                            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                        </div>
                    </div>
                </div>

                <!-- Search Results -->
                <div class="max-h-64 overflow-y-auto">
                    @if (count($searchResults) > 0)
                        @foreach ($searchResults as $result)
                            <div wire:click="selectGood({{ $result['id'] }})"
                                class="px-4 py-3 hover:bg-gray-700 cursor-pointer border-b border-gray-700 last:border-b-0 transition-colors">
                                <div class="text-white font-medium">{{ $result['name'] }}</div>
                                <div class="text-gray-400 text-sm">ID: {{ $result['id'] }}</div>
                            </div>
                        @endforeach
                    @elseif(strlen($search) > 0)
                        <div class="p-4 text-center text-gray-400">
                            No results found for "{{ $search }}"
                        </div>
                    @else
                        <div class="p-4 text-center text-gray-400">
                            Start typing to search for goods...
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let movingAverageChart = null;

        // Initialize chart when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking for chart element...');
            initializeChart();
        });

        // Livewire event listeners
        document.addEventListener('livewire:load', function() {
            console.log('Livewire loaded successfully');

            // Listen for chart updates from Livewire
            Livewire.on('updateMovingAverageChart', function(chartData) {
                console.log('Received chart data:', chartData);
                updateChart(chartData);
            });
        });

        // Also listen for Livewire v3 events
        document.addEventListener('livewire:initialized', function() {
            console.log('Livewire initialized');

            Livewire.on('updateMovingAverageChart', (chartData) => {
                console.log('V3: Received chart data:', chartData);
                updateChart(chartData);
            });
        });

        // Listen for browser events
        window.addEventListener('chartDataUpdated', function(event) {
            console.log('Browser event received:', event.detail);
            updateChart(event.detail);
        });

        function initializeChart() {
            const ctx = document.getElementById('movingAverageChart');
            if (!ctx) {
                console.log('Chart canvas not found');
                return;
            }

            if (movingAverageChart) {
                movingAverageChart.destroy();
            }

            const chartLabels = @json($chartData['labels'] ?? []);
            const chartDatasets = @json($chartData['datasets'] ?? []);

            console.log('Initializing chart with data:', {
                labels: chartLabels,
                datasets: chartDatasets
            });

            movingAverageChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: chartDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: false
                        },
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Quantity (Units)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return Math.round(value) + ' units';
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            },
                            ticks: {
                                maxTicksLimit: 10
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    elements: {
                        point: {
                            radius: 3,
                            hoverRadius: 6
                        },
                        line: {
                            tension: 0.1
                        }
                    }
                }
            });
        }

        function updateChart(chartData) {
            console.log('Updating chart with data:', chartData);
            if (!chartData) {
                console.log('No chart data provided');
                return;
            }

            if (movingAverageChart && chartData) {
                movingAverageChart.data.labels = chartData.labels || [];
                movingAverageChart.data.datasets = chartData.datasets || [];
                movingAverageChart.update('active');
                console.log('Chart updated successfully');
            } else {
                console.log('Chart not initialized, reinitializing...');
                setTimeout(() => {
                    initializeChart();
                }, 100);
            }
        }

        // Keyboard shortcuts for modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && @this.showSearchModal) {
                @this.call('closeSearchModal');
            }
        });
    </script>
@endpush

<style>
    /* Custom styles for better UX */
    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }
</style>
