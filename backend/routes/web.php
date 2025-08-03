<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Livewire\HeroSection\Index as HeroSectionIndex;
use App\Http\Livewire\Brand\Index as BrandIndex;





// Redirect root to company profile (React app)
Route::get('/', function () {
    return redirect('http://localhost:3000');
});

// SSO Login route untuk redirect dari React
Route::get('/sso-login/{userId}', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'ssoLogin'])->name('sso.login');

// Customer routes (Livewire dashboard) - using customer guard
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/customer/dashboard', [DashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/customer/{id}', App\Http\Livewire\Master\CustomerDetail::class)->name('customer.detail');
    Route::get('/customer/detail-transaksi/{id}', App\Http\Livewire\Transaction\Detail::class)->name('customer.transaction.detail');
    Route::get('/customer/pengiriman-barang/{id}', App\Http\Livewire\Delivery\Detail::class)->name('customer.delivery.detail');
});

// Main dashboard for Admin & Super Admin
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/sales-percentage-by-category', [DashboardController::class, 'getSalesPercentageByCategory']);


    // SUPER ADMIN ACCESS
    Route::middleware(['role:super_admin'])->group(function () {
        // Data Admin
        Route::get('data-admin', App\Http\Livewire\Master\Admin::class)->name('master.admin');
        Route::get('tambah-data-admin', App\Http\Livewire\Master\AdminForm::class)->name('master.create-admin');
        Route::get('ubah-data-admin/{id}', App\Http\Livewire\Master\AdminForm::class)->name('master.update-admin');

        // Data Pegawai
        Route::get('data-employee', App\Http\Livewire\Master\Employee::class)->name('master.employee');
        Route::get('tambah-data-pegawai', App\Http\Livewire\Master\EmployeeForm::class)->name('master.create-employee');
        Route::get('ubah-data-pegawai/{id}', App\Http\Livewire\Master\EmployeeForm::class)->name('master.update-employee');

        Route::get('absensi', App\Http\Livewire\Attendace\Index::class)->name('attendance.index');
        Route::get('absensi-baru', App\Http\Livewire\Attendace\Create::class)->name('attendance.create');
        Route::get('absensi-hari-ini/{id}', App\Http\Livewire\Attendace\Create::class)->name('attendance.update');
        Route::get('detail-absensi/{id}', App\Http\Livewire\Attendace\Detail::class)->name('attendance.detail');

        // Data Supplier
        Route::get('data-supplier', App\Http\Livewire\Master\Supplier::class)->name('master.supplier');
        Route::get('tambah-data-supplier', App\Http\Livewire\Master\SupplierForm::class)->name('master.create-supplier');
        Route::get('ubah-data-supplier/{id}', App\Http\Livewire\Master\SupplierForm::class)->name('master.update-supplier');

        // Data Customer
        Route::get('data-customer', App\Http\Livewire\Master\Customer::class)->name('master.customer');
        Route::get('tambah-data-customer', App\Http\Livewire\Master\CustomerForm::class)->name('master.create-customer');
        Route::get('ubah-data-customer/{id}', App\Http\Livewire\Master\CustomerForm::class)->name('master.update-customer');
        Route::get('detail-data-customer/{id}', App\Http\Livewire\Master\CustomerDetail::class)->name('master.detail-customer');

        // Data Laporan
        Route::get('laporan-penjualan', App\Http\Livewire\Report\Index::class)->name('report.index');
        Route::get('laporan-barang', App\Http\Livewire\Report\Goods::class)->name('report.goods');
    });

    // ADMIN ACCESS
    Route::middleware(['role:admin,super_admin'])->group(function () {

        // Data Hutang
        Route::get('data-hutang', App\Http\Livewire\Debt\Index::class)->name('debt.index');

        // Transaksi
        Route::get('transaksi', App\Http\Livewire\Transaction\Create::class)->name('transaction.create');
        Route::get('riwayat-transaksi', App\Http\Livewire\Transaction\History::class)->name('transaction.history');
        Route::get('detail-transaksi/{id}', App\Http\Livewire\Transaction\Detail::class)->name('transaction.detail');
        Route::get('pengiriman-barang', App\Http\Livewire\Delivery\Index::class)->name('delivery.index');
        Route::get('pengiriman-barang/{id}', App\Http\Livewire\Delivery\Detail::class)->name('delivery.detail');
        Route::get('invoice/{id}', App\Http\Livewire\Transaction\Invoice::class)->name('transaction.invoice');
        Route::get('mini-invoice/{id}', App\Http\Livewire\Transaction\MiniInvoice::class)->name('transaction.mini-invoice');

        // Barang
        Route::get('brand-baru', App\Http\Livewire\Brand\Form::class)->name('goods.brand-create');
        Route::get('ubah-brand/{id}', App\Http\Livewire\Brand\Form::class)->name('goods.brand-update');
        Route::get('kategori-baru', App\Http\Livewire\Category\Form::class)->name('goods.category-create');
        Route::get('ubah-kategori/{id}', App\Http\Livewire\Category\Form::class)->name('goods.category-update');
        Route::get('data-barang', App\Http\Livewire\Goods\Data::class)->name('goods.data');
        Route::get('tambah-data-barang', App\Http\Livewire\Goods\Form::class)->name('goods.create');
        Route::get('ubah-data-barang/{id}', App\Http\Livewire\Goods\Form::class)->name('goods.update');
        Route::get('kelola-data-barang', App\Http\Livewire\Goods\Management::class)->name('goods.management');
        Route::get('retur-barang', App\Http\Livewire\Retur\Create::class)->name('goods.retur');
        Route::get('detail-retur/{id}', App\Http\Livewire\Retur\Detail::class)->name('goods.retur-detail');

        // Data Order
        Route::get('data-order', App\Http\Livewire\Order\Index::class)->name('order.index');
        Route::get('order', App\Http\Livewire\Order\Create::class)->name('order.create');
        Route::get('detail-order/{id}', App\Http\Livewire\Order\Detail::class)->name('order.detail');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// CONTENT ADMIN ACCESS (Company Profile Management)
Route::middleware(['auth', 'role:content-admin', 'track.visitor'])->group(function () {
    Route::get('/content/dashboard', function () {
        return view('content.dashboard');
    })->name('content.dashboard');

    // Hero Section Management
    Route::get('/content/hero-sections', HeroSectionIndex::class)->name('content.hero-sections');

    // Brand Management
    Route::get('/content/brands', BrandIndex::class)->name('content.brands');

    // Team Management
    Route::get('/content/teams', App\Http\Livewire\Team\Index::class)->name('content.teams');
    Route::get('/content/teams/create', App\Http\Livewire\Team\Form::class)->name('content.teams.create');
    Route::get('/content/teams/{id}/edit', App\Http\Livewire\Team\Form::class)->name('content.teams.edit');

    // Services Management
    Route::get('/content/services', App\Http\Livewire\Service\Index::class)->name('content.services');
    Route::get('/content/services/create', App\Http\Livewire\Service\Form::class)->name('content.services.create');
    Route::get('/content/services/{id}/edit', App\Http\Livewire\Service\Form::class)->name('content.services.edit');

    // About Management
    Route::get('/content/about', App\Http\Livewire\About\Index::class)->name('content.about');
    Route::get('/content/about/create', App\Http\Livewire\About\Form::class)->name('content.about.create');
    Route::get('/content/about/{id}/edit', App\Http\Livewire\About\Form::class)->name('content.about.edit');

    // Contact Management (placeholder - akan dibuat nanti)
    Route::get('/content/contact', function () {
        return view('content.contact.index');
    })->name('content.contact');

    Route::get('/content/analytics', function () {
        return view('content.analytics');
    })->name('content.analytics');
});

require __DIR__ . '/auth.php';
