@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Customer Dashboard</h1>
        <p class="text-gray-600">Selamat datang di dashboard customer Azhar Material</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-semibold text-gray-900">Profile</h3>
            </div>
            <p class="text-gray-600 mb-4">Kelola informasi profil Anda</p>
            @if(auth()->guard('customer')->check())
                <a href="{{ route('customer.detail', ['id' => auth()->guard('customer')->user()->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Lihat Profile
                </a>
            @elseif(auth()->guard('web')->check() && auth()->user()->role === 'customer')
                <a href="{{ route('customer.detail', ['id' => auth()->user()->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Lihat Profile
                </a>
            @endif
        </div>

        <!-- Transactions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-semibold text-gray-900">Transaksi</h3>
            </div>
            <p class="text-gray-600 mb-4">Lihat riwayat transaksi Anda</p>
            @if(auth()->guard('customer')->check())
                <a href="{{ route('customer.transaction.detail', ['id' => auth()->guard('customer')->user()->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Lihat Transaksi
                </a>
            @elseif(auth()->guard('web')->check() && auth()->user()->role === 'customer')
                <a href="{{ route('customer.transaction.detail', ['id' => auth()->user()->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Lihat Transaksi
                </a>
            @endif
        </div>

        <!-- Deliveries -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-semibold text-gray-900">Pengiriman</h3>
            </div>
            <p class="text-gray-600 mb-4">Track status pengiriman barang</p>
            @if(auth()->guard('customer')->check())
                <a href="{{ route('customer.delivery.detail', ['id' => auth()->guard('customer')->user()->id]) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                    Track Pengiriman
                </a>
            @elseif(auth()->guard('web')->check() && auth()->user()->role === 'customer')
                <a href="{{ route('customer.delivery.detail', ['id' => auth()->user()->id]) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                    Track Pengiriman
                </a>
            @endif
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6">
        @if(auth()->guard('customer')->check())
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Selamat Datang, {{ auth()->guard('customer')->user()->name }}!</h2>
        @elseif(auth()->guard('web')->check() && auth()->user()->role === 'customer')
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
        @else
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Selamat Datang!</h2>
        @endif
        <p class="text-gray-600">
            Anda dapat mengakses semua layanan customer melalui dashboard ini. 
            Silakan pilih menu yang ingin Anda akses di atas.
        </p>
    </div>
</div>
@endsection 