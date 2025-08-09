<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\HeroSection\Index as HeroSectionIndex;
use App\Http\Livewire\Brand\Index as BrandIndex;
use App\Http\Livewire\Transaction\Create as TransactionCreate;
use App\Http\Livewire\Transaction\History as TransactionHistory;
use App\Http\Livewire\Transaction\Detail as TransactionDetail;
use App\Http\Livewire\Transaction\Invoice;
use App\Http\Livewire\Chart;
use App\Http\Livewire\Brand\Form as BrandForm;
use App\Http\Controllers\DashboardController;
use App\Http\Livewire\Transaction\MiniInvoice;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Livewire\Master\CustomerDetail;
use App\Http\Livewire\Customer\Dashboard as CustomerDashboard;
use App\Http\Livewire\Master\Admin as MasterAdmin;
use App\Http\Livewire\Master\AdminForm as MasterAdminForm;
use App\Http\Livewire\Master\Employee as MasterEmployee;
use App\Http\Livewire\Master\EmployeeForm as MasterEmployeeForm;
use App\Http\Livewire\Master\Supplier as MasterSupplier;
use App\Http\Livewire\Master\SupplierForm as MasterSupplierForm;
use App\Http\Livewire\Master\Customer as MasterCustomer;
use App\Http\Livewire\Master\CustomerForm as MasterCustomerForm;
use App\Http\Livewire\Attendace\Index as AttendanceIndex;
use App\Http\Livewire\Attendace\Create as AttendanceCreate;
use App\Http\Livewire\Attendace\Detail as AttendanceDetail;
use App\Http\Livewire\Category\Index as CategoryIndex;
use App\Http\Livewire\Category\Form as CategoryForm;
use App\Http\Livewire\Report\Index as ReportIndex;
use App\Http\Livewire\Report\Goods as ReportGoods;
use App\Http\Livewire\Debt\Index as DebtIndex;
use App\Http\Livewire\Delivery\Index as DeliveryIndex;
use App\Http\Livewire\Delivery\Detail as DeliveryDetail;
use App\Http\Livewire\Goods\Data as GoodsData;
use App\Http\Livewire\Goods\Form as GoodsForm;
use App\Http\Livewire\Goods\Management as GoodsManagement;
use App\Http\Livewire\Retur\Create as ReturCreate;
use App\Http\Livewire\Retur\Detail as ReturDetail;
use App\Http\Livewire\Order\Index as OrderIndex;
use App\Http\Livewire\Order\Create as OrderCreate;
use App\Http\Livewire\Order\Detail as OrderDetail;
use App\Http\Livewire\Team\Index as TeamIndex;
use App\Http\Livewire\Team\Form as TeamForm;
use App\Http\Livewire\Service\Index as ServiceIndex;
use App\Http\Livewire\Service\Form as ServiceForm;
use App\Http\Livewire\About\Index as AboutIndex;
use App\Http\Livewire\About\Form as AboutForm;
use App\Http\Livewire\Content\Analytics as ContentAnalytics;
use App\Http\Livewire\Content\Dashboard as ContentDashboard;
use App\Http\Livewire\Project\Index as ProjectIndex;
use App\Http\Livewire\Project\Form as ProjectForm;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- Rute Publik untuk React (SPA) ---
Route::get('/', function () {
    return view('react');
});
Route::get('/login', function () {
    return view('react');
})->name('login')->middleware('guest:web,customer');

// Rute SSO Login sudah tidak diperlukan karena alur login baru
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Rute-rute publik lainnya yang memuat aplikasi React
Route::get('/products', function () {
    return view('react');
})->name('products');
Route::get('/project', function () {
    return view('react');
})->name('project');
Route::get('/projects', function () {
    return view('react');
})->name('projects');
Route::get('/services', function () {
    return view('react');
})->name('services');
Route::get('/contact', function () {
    return view('react');
})->name('contact');
Route::get('/team', function () {
    return view('react');
})->name('team');
Route::get('/about', function () {
    return view('react');
})->name('about');


// --- Rute Livewire yang Dilindungi (Middleware auth:web) ---

// Grup rute untuk Customer
Route::middleware(['auth:customer', 'verify.customer'])->group(function () {
    Route::get('customer/{id}', App\Http\Livewire\Master\CustomerDetail::class)
        ->name('customer.dashboard');
});

// Route untuk detail transaksi dan pengiriman dengan verifikasi customer_id di database
Route::middleware(['auth:customer'])->group(function () {
    Route::get('detail-transaksi/{id}', TransactionDetail::class)
        ->middleware('verify.customer.transaction:transaction')
        ->name('customer.transaction.detail');
    Route::get('pengiriman-barang/{id}', DeliveryDetail::class)
        ->middleware('verify.customer.transaction:delivery')
        ->name('customer.delivery.detail');
});

// Grup rute untuk Admin dan Superadmin sekarang dipisahkan ke file rute mereka sendiri
// dan didaftarkan di RouteServiceProvider.

// Grup rute untuk Owner
Route::middleware(['auth:web', 'role:owner'])->prefix('owner')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/chart', App\Http\Livewire\Chart::class)->name('owner.chart');
    Route::get('laporan-penjualan', ReportIndex::class)->name('owner.report.index');
    Route::get('laporan-barang', ReportGoods::class)->name('owner.report.goods');
    Route::get('data-barang', GoodsData::class)->name('owner.goods.data');
    Route::get('tambah-data-barang', GoodsForm::class)->name('owner.goods.create');
    Route::get('ubah-data-barang/{id}', GoodsForm::class)->name('owner.goods.update');
    Route::get('kelola-stok-barang', GoodsManagement::class)->name('owner.goods.stock');
    Route::get('data-kategori', CategoryIndex::class)->name('owner.category.index');
    Route::get('kategori-baru', CategoryForm::class)->name('owner.category.create');
    Route::get('ubah-kategori/{id}', CategoryForm::class)->name('owner.category.update');

    // Brand Routes
    Route::get('data-brand', BrandIndex::class)->name('owner.brand.index');
    Route::get('brand-baru', BrandForm::class)->name('owner.brand.create');
    Route::get('ubah-brand/{id}', BrandForm::class)->name('owner.brand.update');
});

Route::middleware(['auth:web', 'role:driver'])->prefix('driver')->group(function () {
    Route::get('/pengiriman-barang', DeliveryIndex::class)->name('driver.delivery.index');
    Route::get('/pengiriman-barang/{id}', DeliveryDetail::class)->name('driver.delivery.detail');
});

// Grup rute untuk Content Admin
Route::middleware(['auth:web', 'role:content-admin'])->prefix('content-admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', ContentDashboard::class)->name('content-admin.dashboard');
    Route::get('/analytics', ContentAnalytics::class)->name('content-admin.analytics');
    // Goods Routes
    Route::get('data-barang', GoodsData::class)->name('content-admin.goods.data');
    Route::get('tambah-data-barang', GoodsForm::class)->name('content-admin.goods.create');
    Route::get('ubah-data-barang/{id}', GoodsForm::class)->name('content-admin.goods.update');
    Route::get('kelola-stok-barang', GoodsManagement::class)->name('content-admin.goods.stock');
    Route::get('data-kategori', CategoryIndex::class)->name('content-admin.category.index');
    Route::get('kategori-baru', CategoryForm::class)->name('content-admin.category.create');
    Route::get('ubah-kategori/{id}', CategoryForm::class)->name('content-admin.category.update');

    // Brand Routes
    Route::get('data-brand', BrandIndex::class)->name('content-admin.brand.index');
    Route::get('brand-baru', BrandForm::class)->name('content-admin.brand.create');
    Route::get('ubah-brand/{id}', BrandForm::class)->name('content-admin.brand.update');

    // Hero Sections
    Route::get('/hero-sections', HeroSectionIndex::class)->name('content-admin.hero-sections');
    Route::get('/hero-sections/create', App\Http\Livewire\HeroSection\Form::class)->name('content-admin.hero-sections.create');
    Route::get('/hero-sections/{id}/edit', App\Http\Livewire\HeroSection\Form::class)->name('content-admin.hero-sections.edit');

    // Projects
    Route::get('/projects', ProjectIndex::class)->name('content-admin.projects');
    Route::get('/projects/create', ProjectForm::class)->name('content-admin.projects.create');
    Route::get('/projects/{id}/edit', ProjectForm::class)->name('content-admin.projects.edit');

    // Brand Images Management - akan dibuat nanti
    // Route::get('/brands', App\Http\Livewire\Brand\ImageManager::class)->name('content-admin.brands');

    // Product Images Management - akan dibuat nanti
    // Route::get('/products', App\Http\Livewire\Goods\ImageManager::class)->name('content-admin.products');
    Route::get('data-brand', BrandIndex::class)->name('content-admin.brand.index');
    Route::get('brand-baru', BrandForm::class)->name('content-admin.brand.create');
    Route::get('form-logo', App\Http\Livewire\Brand\FormLogo::class)->name('content-admin.brand.form-logo');
    Route::get('form-logo/{id}/edit', App\Http\Livewire\Brand\FormLogo::class)->name('content-admin.brand.form-logo.edit');
    // Teams
    Route::get('/teams', TeamIndex::class)->name('content-admin.teams');
    Route::get('/teams/create', TeamForm::class)->name('content-admin.teams.create');
    Route::get('/teams/{id}/edit', TeamForm::class)->name('content-admin.teams.edit');

    // Services
    Route::get('/services', ServiceIndex::class)->name('content-admin.services');
    Route::get('/services/create', ServiceForm::class)->name('content-admin.services.create');
    Route::get('/services/{id}/edit', ServiceForm::class)->name('content-admin.services.edit');

    // About
    Route::get('/about', AboutIndex::class)->name('content-admin.about');
    Route::get('/about/create', AboutForm::class)->name('content-admin.about.create');
    Route::get('/about/{id}/edit', AboutForm::class)->name('content-admin.about.edit');
});

// Rute Profil Pengguna (berbagi untuk semua peran)
Route::middleware('auth:web')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Catch-all route untuk SPA - HARUS DI PALING BAWAH
// Ini akan menangani semua route yang tidak terdefinisi dan mengembalikan React app
Route::fallback(function () {
    return view('react');
});
