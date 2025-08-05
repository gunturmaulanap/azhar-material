<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChartController;
use App\Http\Controllers\DashboardController;
use App\Http\Livewire\Master\Admin as MasterAdmin;
use App\Http\Livewire\Master\AdminForm as MasterAdminForm;
use App\Http\Livewire\Master\Employee as MasterEmployee;
use App\Http\Livewire\Master\EmployeeForm as MasterEmployeeForm;
use App\Http\Livewire\Master\Supplier as MasterSupplier;
use App\Http\Livewire\Master\SupplierForm as MasterSupplierForm;
use App\Http\Livewire\Master\Customer as MasterCustomer;
use App\Http\Livewire\Master\CustomerForm as MasterCustomerForm;
use App\Http\Livewire\Master\CustomerDetail;
use App\Http\Livewire\Attendace\Index as AttendanceIndex;
use App\Http\Livewire\Attendace\Create as AttendanceCreate;
use App\Http\Livewire\Attendace\Detail as AttendanceDetail;
use App\Http\Livewire\Category\Index as CategoryIndex;
use App\Http\Livewire\Category\Form as CategoryForm;
use App\Http\Livewire\Brand\Index as BrandIndex;
use App\Http\Livewire\Brand\Form as BrandForm;
use App\Http\Livewire\Report\Index as ReportIndex;
use App\Http\Livewire\Report\Goods as ReportGoods;
use App\Http\Livewire\Debt\Index as DebtIndex;
use App\Http\Livewire\Transaction\Create as TransactionCreate;
use App\Http\Livewire\Transaction\History as TransactionHistory;
use App\Http\Livewire\Transaction\Detail as TransactionDetail;
use App\Http\Livewire\Delivery\Index as DeliveryIndex;
use App\Http\Livewire\Delivery\Detail as DeliveryDetail;
use App\Http\Livewire\Transaction\Invoice;
use App\Http\Livewire\Transaction\MiniInvoice;
use App\Http\Livewire\Goods\Data as GoodsData;
use App\Http\Livewire\Goods\Form as GoodsForm;
use App\Http\Livewire\Goods\Management as GoodsManagement;
use App\Http\Livewire\Retur\Create as ReturCreate;
use App\Http\Livewire\Retur\Detail as ReturDetail;
use App\Http\Livewire\Order\Index as OrderIndex;
use App\Http\Livewire\Order\Create as OrderCreate;
use App\Http\Livewire\Order\Detail as OrderDetail;

// Rute untuk Superadmin
Route::middleware(['web', 'auth:web', 'role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/chart', [ChartController::class, 'index'])->name('chart');
    Route::get('/chart', [ChartController::class, 'index'])->name('chart');
    Route::get('/api/sales-percentage-by-category', [DashboardController::class, 'getSalesPercentageByCategory']);

    // Master Data Routes
    Route::get('data-admin', MasterAdmin::class)->name('master.admin');
    Route::get('tambah-data-admin', MasterAdminForm::class)->name('master.create-admin');
    Route::get('ubah-data-admin/{id}', MasterAdminForm::class)->name('master.update-admin');

    Route::get('data-employee', MasterEmployee::class)->name('master.employee');
    Route::get('tambah-data-pegawai', MasterEmployeeForm::class)->name('master.create-employee');
    Route::get('ubah-data-pegawai/{id}', MasterEmployeeForm::class)->name('master.update-employee');

    Route::get('data-supplier', MasterSupplier::class)->name('master.supplier');
    Route::get('tambah-data-supplier', MasterSupplierForm::class)->name('master.create-supplier');
    Route::get('ubah-data-supplier/{id}', MasterSupplierForm::class)->name('master.update-supplier');

    Route::get('data-customer', MasterCustomer::class)->name('master.customer');
    Route::get('tambah-data-customer', MasterCustomerForm::class)->name('master.create-customer');
    Route::get('ubah-data-customer/{id}', MasterCustomerForm::class)->name('master.update-customer');
    Route::get('detail-data-customer/{id}', CustomerDetail::class)->name('master.detail-customer');

    // Attendance Routes
    Route::get('absensi', AttendanceIndex::class)->name('attendance.index');
    Route::get('absensi-baru', AttendanceCreate::class)->name('attendance.create');
    Route::get('absensi-hari-ini/{id}', AttendanceCreate::class)->name('attendance.update');
    Route::get('detail-absensi/{id}', AttendanceDetail::class)->name('attendance.detail');

    // Category Routes
    Route::get('data-kategori', CategoryIndex::class)->name('category.index');
    Route::get('kategori-baru', CategoryForm::class)->name('category.create');
    Route::get('ubah-kategori/{id}', CategoryForm::class)->name('category.update');

    // Brand Routes
    Route::get('data-brand', BrandIndex::class)->name('brand.index');
    Route::get('brand-baru', BrandForm::class)->name('brand.create');
    Route::get('ubah-brand/{id}', BrandForm::class)->name('brand.update');

    // Report Routes
    Route::get('laporan-penjualan', ReportIndex::class)->name('report.index');
    Route::get('laporan-barang', ReportGoods::class)->name('report.goods');

    // Debt Routes
    Route::get('data-hutang', DebtIndex::class)->name('debt.index');

    // Transaction Routes
    Route::get('transaksi', TransactionCreate::class)->name('transaction.create');
    Route::get('riwayat-transaksi', TransactionHistory::class)->name('transaction.history');
    Route::get('detail-transaksi/{id}', TransactionDetail::class)->name('transaction.detail');
    Route::get('invoice/{id}', Invoice::class)->name('transaction.invoice');
    Route::get('mini-invoice/{id}', MiniInvoice::class)->name('transaction.mini-invoice');

    // Delivery Routes
    Route::get('pengiriman-barang', DeliveryIndex::class)->name('delivery.index');
    Route::get('pengiriman-barang/{id}', DeliveryDetail::class)->name('delivery.detail');

    // Goods Routes
    Route::get('data-barang', GoodsData::class)->name('goods.data');
    Route::get('tambah-data-barang', GoodsForm::class)->name('goods.create');
    Route::get('ubah-data-barang/{id}', GoodsForm::class)->name('goods.update');
    Route::get('kelola-data-barang', GoodsManagement::class)->name('goods.management');

    // Retur Routes
    Route::get('retur-barang', ReturCreate::class)->name('goods.retur');
    Route::get('detail-retur/{id}', ReturDetail::class)->name('goods.retur-detail');

    // Order Routes
    Route::get('data-order', OrderIndex::class)->name('order.index');
    Route::get('order', OrderCreate::class)->name('order.create');
    Route::get('detail-order/{id}', OrderDetail::class)->name('order.detail');
});
