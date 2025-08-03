<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\HeroSection\Index as HeroSectionIndex;
use App\Http\Livewire\Brand\Index as BrandIndex;
use App\Http\Livewire\Transaction\Pos;
use App\Http\Livewire\Transaction\CreditSales;
use App\Http\Livewire\Transaction\Create as TransactionCreate;
use App\Http\Livewire\Transaction\History as TransactionHistory;
use App\Http\Livewire\Transaction\Detail as TransactionDetail;
use App\Http\Livewire\Transaction\Invoice;
use App\Http\Livewire\Transaction\MiniInvoice;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// React SPA Routes - These will be handled by React Router
Route::get('/', function () {
    return view('react');
})->name('home');

Route::get('/products', function () {
    return view('react');
})->name('products');

Route::get('/brands', function () {
    return view('react');
})->name('brands');

Route::get('/services', function () {
    return view('react');
})->name('services');

Route::get('/contact', function () {
    return view('react');
})->name('contact');

Route::get('/team', function () {
    return view('react');
})->name('team');

Route::get('/login', function () {
    return view('react');
})->name('frontend.login');

// Admin React Routes
Route::get('/admin/dashboard', function () {
    return view('react');
})->name('admin.dashboard');

Route::get('/admin/content', function () {
    return view('react');
})->name('admin.content');

// Customer routes (Livewire) - hybrid for both guards
Route::get('/customer/{id}', [App\Http\Livewire\Master\CustomerDetail::class, 'show'])->middleware('auth:customer,web')
    ->name('customer.dashboard');

// Customer transaction tracking routes
Route::get('/customer/detail-transaksi/{id}', TransactionDetail::class)->name('customer.transaction.detail');
Route::get('/customer/pengiriman-barang/{id}', App\Http\Livewire\Delivery\Detail::class)->name('customer.delivery.detail');

/*
|--------------------------------------------------------------------------
| Superadmin Only Routes
| Full access to all master data, categories, transactions, orders, goods, reports, delivery, debt
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'role:super_admin'])->group(function () {

    // Master Data Management
    Route::get('data-admin', App\Http\Livewire\Master\Admin::class)->name('master.admin');
    Route::get('tambah-data-admin', App\Http\Livewire\Master\AdminForm::class)->name('master.create-admin');
    Route::get('ubah-data-admin/{id}', App\Http\Livewire\Master\AdminForm::class)->name('master.update-admin');

    // Employee Management
    Route::get('data-employee', App\Http\Livewire\Master\Employee::class)->name('master.employee');
    Route::get('tambah-data-pegawai', App\Http\Livewire\Master\EmployeeForm::class)->name('master.create-employee');
    Route::get('ubah-data-pegawai/{id}', App\Http\Livewire\Master\EmployeeForm::class)->name('master.update-employee');

    // Supplier Management  
    Route::get('data-supplier', App\Http\Livewire\Master\Supplier::class)->name('master.supplier');
    Route::get('tambah-data-supplier', App\Http\Livewire\Master\SupplierForm::class)->name('master.create-supplier');
    Route::get('ubah-data-supplier/{id}', App\Http\Livewire\Master\SupplierForm::class)->name('master.update-supplier');

    // Customer Management
    Route::get('data-customer', App\Http\Livewire\Master\Customer::class)->name('master.customer');
    Route::get('tambah-data-customer', App\Http\Livewire\Master\CustomerForm::class)->name('master.create-customer');
    Route::get('ubah-data-customer/{id}', App\Http\Livewire\Master\CustomerForm::class)->name('master.update-customer');
    Route::get('detail-data-customer/{id}', App\Http\Livewire\Master\CustomerDetail::class)->name('master.detail-customer');

    // Attendance Management
    Route::get('absensi', App\Http\Livewire\Attendace\Index::class)->name('attendance.index');
    Route::get('absensi-baru', App\Http\Livewire\Attendace\Create::class)->name('attendance.create');
    Route::get('absensi-hari-ini/{id}', App\Http\Livewire\Attendace\Create::class)->name('attendance.update');
    Route::get('detail-absensi/{id}', App\Http\Livewire\Attendace\Detail::class)->name('attendance.detail');

    // Categories Management
    Route::get('data-kategori', App\Http\Livewire\Category\Index::class)->name('category.index');
    Route::get('kategori-baru', App\Http\Livewire\Category\Form::class)->name('category.create');
    Route::get('ubah-kategori/{id}', App\Http\Livewire\Category\Form::class)->name('category.update');

    // Brand Management (Superadmin) - Also used in goods management
    Route::get('data-brand', App\Http\Livewire\Brand\Index::class)->name('brand.index');
    Route::get('brand-baru', App\Http\Livewire\Brand\Form::class)->name('brand.create');
    Route::get('ubah-brand/{id}', App\Http\Livewire\Brand\Form::class)->name('brand.update');

    // Reports
    Route::get('laporan-penjualan', App\Http\Livewire\Report\Index::class)->name('report.index');
    Route::get('laporan-barang', App\Http\Livewire\Report\Goods::class)->name('report.goods');

    // POS System and Financial
    // Route::get('point-of-sale', Pos::class)->name('pos.index');
    // Route::get('kredit-penjualan', CreditSales::class)->name('transaction.credit-sales');
    Route::get('data-hutang', App\Http\Livewire\Debt\Index::class)->name('debt.index');

    // Transaction Management - HANYA SUPERADMIN
    Route::get('transaksi', TransactionCreate::class)->name('transaction.create');
    Route::get('riwayat-transaksi', TransactionHistory::class)->name('transaction.history');
    Route::get('detail-transaksi/{id}', TransactionDetail::class)->name('transaction.detail');
    Route::get('pengiriman-barang', App\Http\Livewire\Delivery\Index::class)->name('delivery.index');
    Route::get('pengiriman-barang/{id}', App\Http\Livewire\Delivery\Detail::class)->name('delivery.detail');
    Route::get('invoice/{id}', Invoice::class)->name('transaction.invoice');
    Route::get('mini-invoice/{id}', MiniInvoice::class)->name('transaction.mini-invoice');

    // Goods Management - HANYA SUPERADMIN
    Route::get('data-barang', App\Http\Livewire\Goods\Data::class)->name('goods.data');
    Route::get('tambah-data-barang', App\Http\Livewire\Goods\Form::class)->name('goods.create');
    Route::get('ubah-data-barang/{id}', App\Http\Livewire\Goods\Form::class)->name('goods.update');
    Route::get('kelola-data-barang', App\Http\Livewire\Goods\Management::class)->name('goods.management');

    // Brand CRUD within goods management
    Route::get('data-barang/brand', App\Http\Livewire\Brand\Index::class)->name('goods.brand.index');
    Route::get('data-barang/brand-baru', App\Http\Livewire\Brand\Form::class)->name('goods.brand.create');
    Route::get('data-barang/ubah-brand/{id}', App\Http\Livewire\Brand\Form::class)->name('goods.brand.update');

    // Category CRUD within goods management
    Route::get('data-barang/kategori', App\Http\Livewire\Category\Index::class)->name('goods.category.index');
    Route::get('data-barang/kategori-baru', App\Http\Livewire\Category\Form::class)->name('goods.category.create');
    Route::get('data-barang/ubah-kategori/{id}', App\Http\Livewire\Category\Form::class)->name('goods.category.update');

    Route::get('retur-barang', App\Http\Livewire\Retur\Create::class)->name('goods.retur');
    Route::get('detail-retur/{id}', App\Http\Livewire\Retur\Detail::class)->name('goods.retur-detail');

    // Order Management - HANYA SUPERADMIN
    Route::get('data-order', App\Http\Livewire\Order\Index::class)->name('order.index');
    Route::get('order', App\Http\Livewire\Order\Create::class)->name('order.create');
    Route::get('detail-order/{id}', App\Http\Livewire\Order\Detail::class)->name('order.detail');
});

/*
|--------------------------------------------------------------------------
| Admin Routes - Operational Work Only
| Access: transactions, orders, goods, delivery
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'role:admin'])->group(function () {
    // Transaction Management - Admin
    Route::get('admin/transaksi', TransactionCreate::class)->name('admin.transaction.create');
    Route::get('admin/riwayat-transaksi', TransactionHistory::class)->name('admin.transaction.history');
    Route::get('admin/detail-transaksi/{id}', TransactionDetail::class)->name('admin.transaction.detail');

    // Order Management - Admin
    Route::get('admin/data-order', App\Http\Livewire\Order\Index::class)->name('admin.order.index');
    Route::get('admin/order', App\Http\Livewire\Order\Create::class)->name('admin.order.create');
    Route::get('admin/detail-order/{id}', App\Http\Livewire\Order\Detail::class)->name('admin.order.detail');

    // Goods Management - Admin (View/Edit only)
    Route::get('admin/data-barang', App\Http\Livewire\Goods\Data::class)->name('admin.goods.data');
    Route::get('admin/tambah-data-barang', App\Http\Livewire\Goods\Form::class)->name('admin.goods.create');
    Route::get('admin/ubah-data-barang/{id}', App\Http\Livewire\Goods\Form::class)->name('admin.goods.update');

    // Brand/Category management for Admin (limited)
    Route::get('admin/data-barang/brand', App\Http\Livewire\Brand\Index::class)->name('admin.goods.brand.index');
    Route::get('admin/data-barang/kategori', App\Http\Livewire\Category\Index::class)->name('admin.goods.category.index');

    // Delivery Management - Admin
    Route::get('admin/pengiriman-barang', App\Http\Livewire\Delivery\Index::class)->name('admin.delivery.index');
    Route::get('admin/pengiriman-barang/{id}', App\Http\Livewire\Delivery\Detail::class)->name('admin.delivery.detail');
});

/*
|--------------------------------------------------------------------------
| Owner Routes
| Access: reports, dashboard, goods stock management only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'role:owner'])->group(function () {
    // Reports
    Route::get('owner/laporan-penjualan', App\Http\Livewire\Report\Index::class)->name('owner.report.index');
    Route::get('owner/laporan-barang', App\Http\Livewire\Report\Goods::class)->name('owner.report.goods');

    // Goods Stock Management - Owner can only edit stock
    Route::get('owner/data-barang', App\Http\Livewire\Goods\Data::class)->name('owner.goods.data');
    Route::get('owner/kelola-stok-barang', App\Http\Livewire\Goods\Management::class)->name('owner.goods.stock');
});

/*
|--------------------------------------------------------------------------
| Livewire Admin Authentication
|--------------------------------------------------------------------------
*/
Route::middleware('guest:web')->group(function () {
    Route::get('/admin-login', function () {
        return view('auth.login');
    })->name('admin.login');
});

Route::middleware('auth:web')->group(function () {
    Route::get('/admin', App\Http\Livewire\Dashboard::class)->name('admin.dashboard.livewire');
    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('admin.logout');
});

/*
|--------------------------------------------------------------------------
| Brand Routes - Accessible by both Content-Admin and Super-Admin
| Brand digunakan untuk goods management dan company profile
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'role:content-admin|super_admin'])->prefix('content')->group(function () {
    // Brand Management (Both Content Admin for Company Profile & Super Admin for Goods)
    Route::get('/brands', BrandIndex::class)->name('content.brands');
    Route::get('/brands/create', App\Http\Livewire\Brand\Form::class)->name('content.brands.create');
    Route::get('/brands/{id}/edit', App\Http\Livewire\Brand\Form::class)->name('content.brands.edit');
});

/*
|--------------------------------------------------------------------------
| Content Admin Exclusive Routes
| Hanya content-admin yang bisa akses (hero-sections, teams, services, about)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'role:content-admin'])->prefix('content')->group(function () {
    // Hero Section Management (Content Admin Only)
    Route::get('/hero-sections', HeroSectionIndex::class)->name('content.hero-sections');
    Route::get('/hero-sections/create', App\Http\Livewire\HeroSection\Form::class)->name('content.hero-sections.create');
    Route::get('/hero-sections/{id}/edit', App\Http\Livewire\HeroSection\Form::class)->name('content.hero-sections.edit');

    // Team Management (Content Admin Only)
    Route::get('/teams', App\Http\Livewire\Team\Index::class)->name('content.teams');
    Route::get('/teams/create', App\Http\Livewire\Team\Form::class)->name('content.teams.create');
    Route::get('/teams/{id}/edit', App\Http\Livewire\Team\Form::class)->name('content.teams.edit');

    // Service Management (Content Admin Only)
    Route::get('/services', App\Http\Livewire\Service\Index::class)->name('content.services');
    Route::get('/services/create', App\Http\Livewire\Service\Form::class)->name('content.services.create');
    Route::get('/services/{id}/edit', App\Http\Livewire\Service\Form::class)->name('content.services.edit');

    // About Management (Content Admin Only)
    Route::get('/about', App\Http\Livewire\About\Index::class)->name('content.about');
    Route::get('/about/create', App\Http\Livewire\About\Form::class)->name('content.about.create');
    Route::get('/about/{id}/edit', App\Http\Livewire\About\Form::class)->name('content.about.edit');

    // Analytics (Content Admin Only)
    Route::get('/analytics', App\Http\Livewire\Content\Analytics::class)->name('content.analytics');
});

require __DIR__ . '/auth.php';
