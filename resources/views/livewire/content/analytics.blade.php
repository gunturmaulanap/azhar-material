<div>
    <x-slot name="title">Website Analytics</x-slot>

    <x-slot name="breadcrumb">
        @php($breadcrumb = ['Content', 'Analytics'])
        @foreach ($breadcrumb as $item)
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <span
                    class="block transition hover:text-gray-700 @if ($loop->last) text-gray-950 font-medium @endif">{{ $item }}</span>
            </li>
        @endforeach
    </x-slot>

    <div class="space-y-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Website Analytics</h1>
                    <p class="text-gray-600">Statistik pengunjung website Azhar Material</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Connection status -->
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-3 h-3 rounded-full {{ $isConnected ? 'bg-green-500' : 'bg-red-500' }} animate-pulse">
                        </div>
                        <span
                            class="text-sm text-gray-600">{{ $isConnected ? 'Real-time Connected' : 'Offline Mode' }}</span>
                    </div>

                    <!-- Last update from DB/polling -->
                    @if ($lastUpdate)
                        <div class="text-sm text-gray-500">Last update: {{ $lastUpdate }}</div>
                    @endif

                    <!-- Realtime event indicator -->
                    <div id="rt-indicator" class="flex items-center space-x-2 rounded-md px-2 py-1 ring-0">
                        <span class="text-xs font-medium text-emerald-700 bg-emerald-100 rounded-full px-2 py-0.5">
                            RT events: {{ $rtEvents ?? 0 }}
                        </span>
                        @if ($rtLastAt)
                            <span class="text-xs text-gray-500">last {{ $rtLastAt }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Online visitors -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-emerald-100 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Online Sekarang</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($onlineVisitors ?? 0) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">

                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Pengunjung</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format((int) ($totalVisitors ?? 0)) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format((int) ($todayVisitors ?? 0)) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Minggu Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format((int) ($thisWeekVisitors ?? 0)) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format((int) ($thisMonthVisitors ?? 0)) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Halaman Terpopuler</h3>
                @if ($topPages->count() > 0)
                    <div class="space-y-3">
                        @foreach ($topPages as $page)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">{{ $page->page_visited }}</span>
                                <span class="text-sm font-bold text-blue-600">{{ number_format($page->count) }}
                                    kunjungan</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada data kunjungan</p>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Kunjungan 7 Hari Terakhir</h3>
                @if ($visitorsByDay->count() > 0)
                    <div class="space-y-3">
                        @foreach ($visitorsByDay as $day)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span
                                    class="text-sm font-medium text-gray-700">{{ \Carbon\Carbon::parse($day->visit_date)->format('d M Y') }}</span>
                                <span class="text-sm font-bold text-green-600">{{ number_format($day->count) }}
                                    kunjungan</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada data kunjungan</p>
                @endif
            </div>
        </div> <!-- end second grid -->

        <div class="mt-8 text-center">
            <button id="btnRefreshData" type="button"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                Refresh Data
            </button>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const BASE_URL = @json(env('SOCKET_BASE_URL'));
                const PREFIX = @json(env('SOCKET_PREFIX'));

                let socket = null;
                try {
                    socket = io(BASE_URL || 'http://localhost:3001', {
                        path: '/socket.io',
                        transports: ['websocket', 'polling'],
                        withCredentials: true,
                    });

                    socket.on('connect', () => {
                        console.log('✅ Socket connected', socket.id);
                        @this.set('isConnected', true);
                        if (PREFIX) socket.emit('join', {
                            room: `${PREFIX}-analytics`
                        });
                        socket.emit('request-snapshot');
                    });

                    socket.on('disconnect', () => {
                        console.log('❌ Socket disconnected');
                        @this.set('isConnected', false);
                    });

                    socket.on('analytics-update', (data) => {
                        console.log('📡 analytics-update', data);
                        // persist snapshot so refresh restores immediately
                        try {
                            localStorage.setItem('am_snapshot', JSON.stringify(data));
                        } catch (_) {}
                        @this.call('handleRealTimeUpdate', data);

                        // Flash the realtime indicator to confirm an event arrived
                        const el = document.getElementById('rt-indicator');
                        if (el) {
                            el.classList.add('ring-2', 'ring-emerald-400', 'ring-offset-2');
                            setTimeout(() => el.classList.remove('ring-2', 'ring-emerald-400', 'ring-offset-2'),
                                800);
                        }
                    });

                    // expose for other scripts/debug
                    window.dashboardSocket = socket;

                    // Bind "Refresh Data" button to request snapshot from socket server
                    const btn = document.getElementById('btnRefreshData');
                    if (btn) {
                        btn.addEventListener('click', () => {
                            if (socket && socket.connected) {
                                console.log('↻ requesting realtime snapshot…');
                                socket.emit('request-snapshot');
                            } else {
                                // fallback ke Livewire bila socket tidak connect
                                @this.call('loadAnalytics');
                            }
                        });
                    }
                } catch (e) {
                    console.error('Socket init failed:', e);
                    @this.set('isConnected', false);
                }

                setInterval(() => {
                    if (socket && socket.connected) {
                        socket.emit('request-snapshot');
                    } else {
                        @this.call('loadAnalytics'); // fallback terakhir kalau socket putus
                    }
                }, 30000);
            });
        </script>
    @endpush
