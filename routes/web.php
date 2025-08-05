<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\HeroSection\Index as HeroSectionIndex;
use App\Http\Livewire\Brand\Index as BrandIndex;
use App\Http\Livewire\Transaction\Create as TransactionCreate;
use App\Http\Livewire\Transaction\History as TransactionHistory;
use App\Http\Livewire\Transaction\Detail as TransactionDetail;
use App\Http\Livewire\Transaction\Invoice;
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
})->name('login');

// Rute SSO Login sudah tidak diperlukan karena alur login baru
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Rute-rute publik lainnya yang memuat aplikasi React
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
Route::get('/about', function () {
    return view('react');
})->name('about');


// --- Rute Livewire yang Dilindungi (Middleware auth:web) ---

// Grup rute untuk Customer
Route::middleware(['auth:customer'])->group(function () {
    Route::get('customer/{id}', App\Http\Livewire\Master\CustomerDetail::class)
        ->name('customer.dashboard');

    Route::get('detail-transaksi/{id}', TransactionDetail::class)->name('customer.transaction.detail');
    Route::get('pengiriman-barang/{id}', DeliveryDetail::class)->name('customer.delivery.detail');
});

// Grup rute untuk Admin dan Superadmin sekarang dipisahkan ke file rute mereka sendiri
// dan didaftarkan di RouteServiceProvider.

// Grup rute untuk Owner
Route::middleware(['auth:web', 'role:owner'])->prefix('owner')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('laporan-penjualan', ReportIndex::class)->name('owner.report.index');
    Route::get('laporan-barang', ReportGoods::class)->name('owner.report.goods');
    Route::get('data-barang', GoodsData::class)->name('owner.goods.data');
    Route::get('kelola-stok-barang', GoodsManagement::class)->name('owner.goods.stock');
});

// Grup rute untuk Content Admin
Route::middleware(['auth:web', 'role:content-admin'])->prefix('content')->group(function () {
    Route::get('/brands', BrandIndex::class)->name('content.brands');
    Route::get('/brands/create', App\Http\Livewire\Brand\Form::class)->name('content.brands.create');
    Route::get('/brands/{id}/edit', App\Http\Livewire\Brand\Form::class)->name('content.brands.edit');
    Route::get('/hero-sections', HeroSectionIndex::class)->name('content.hero-sections');
    Route::get('/hero-sections/create', App\Http\Livewire\HeroSection\Form::class)->name('content.hero-sections.create');
    Route::get('/hero-sections/{id}/edit', App\Http\Livewire\HeroSection\Form::class)->name('content.hero-sections.edit');
    Route::get('/teams', TeamIndex::class)->name('content.teams');
    Route::get('/teams/create', TeamForm::class)->name('content.teams.create');
    Route::get('/teams/{id}/edit', TeamForm::class)->name('content.teams.edit');
    Route::get('/services', ServiceIndex::class)->name('content.services');
    Route::get('/services/create', ServiceForm::class)->name('content.services.create');
    Route::get('/services/{id}/edit', ServiceForm::class)->name('content.services.edit');
    Route::get('/about', AboutIndex::class)->name('content.about');
    Route::get('/about/create', AboutForm::class)->name('content.about.create');
    Route::get('/about/{id}/edit', AboutForm::class)->name('content.about.edit');
    Route::get('/analytics', ContentAnalytics::class)->name('content.analytics');
});

// Rute Profil Pengguna (berbagi untuk semua peran)
Route::middleware('auth:web')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
