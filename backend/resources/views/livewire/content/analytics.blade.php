@extends('layouts.app')

@section('title', 'Website Analytics')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Website Analytics</h1>
                    <p class="text-gray-600">Statistik pengunjung website Azhar Material</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Connection Status -->
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full {{ $isConnected ? 'bg-green-500' : 'bg-red-500' }} animate-pulse"></div>
                        <span class="text-sm text-gray-600">{{ $isConnected ? 'Real-time Connected' : 'Offline Mode' }}</span>
                    </div>
                    
                    <!-- Last Update -->
                    @if($lastUpdate)
                    <div class="text-sm text-gray-500">
                        Last update: {{ $lastUpdate }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Visitors -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Pengunjung</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalVisitors) }}</p>
                    </div>
                </div>
            </div>

            <!-- Today's Visitors -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($todayVisitors) }}</p>
                    </div>
                </div>
            </div>

            <!-- This Week's Visitors -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Minggu Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($thisWeekVisitors) }}</p>
                    </div>
                </div>
            </div>

            <!-- This Month's Visitors -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($thisMonthVisitors) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Pages -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Halaman Terpopuler</h3>
                @if($topPages->count() > 0)
                    <div class="space-y-3">
                        @foreach($topPages as $page)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">{{ $page->page_visited }}</span>
                                <span class="text-sm font-bold text-blue-600">{{ number_format($page->count) }} kunjungan</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada data kunjungan</p>
                @endif
            </div>

            <!-- Visitors by Day -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Kunjungan 7 Hari Terakhir</h3>
                @if($visitorsByDay->count() > 0)
                    <div class="space-y-3">
                        @foreach($visitorsByDay as $day)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">{{ \Carbon\Carbon::parse($day->visit_date)->format('d M Y') }}</span>
                                <span class="text-sm font-bold text-green-600">{{ number_format($day->count) }} kunjungan</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada data kunjungan</p>
                @endif
            </div>
        </div>

        <!-- Refresh Button -->
        <div class="mt-8 text-center">
            <button wire:click="loadAnalytics" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh Data
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Connect to Socket.IO server
            const socket = io('http://localhost:3001');

            // Connection status
            socket.on('connect', function() {
                console.log('Connected to Socket.IO server');
                // Update connection status in Livewire
                @this.set('isConnected', true);
            });

            socket.on('disconnect', function() {
                console.log('Disconnected from Socket.IO server');
                @this.set('isConnected', false);
            });

            // Listen for real-time analytics updates
            socket.on('analytics-update', function(data) {
                console.log('Real-time analytics update received:', data);

                // Update Livewire component with new data
                @this.call('handleRealTimeUpdate', data);
            });

            // Track current page visit
            socket.emit('track-visitor', {
                page_visited: window.location.pathname,
                user_agent: navigator.userAgent,
                timestamp: new Date().toISOString()
            });

            // Auto-refresh every 30 seconds as fallback
            setInterval(function() {
                @this.call('loadAnalytics');
            }, 30000);
        });
    </script>
@endpush
