<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Transaction\Create as TransactionCreate;
use App\Http\Livewire\Transaction\History as TransactionHistory;
use App\Http\Livewire\Transaction\Detail as TransactionDetail;
use App\Http\Livewire\Transaction\Invoice;
use App\Http\Livewire\Transaction\MiniInvoice;
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
use App\Http\Livewire\Debt\Index as DebtIndex;
use App\Http\Livewire\Brand\Index as BrandIndex;
use App\Http\Livewire\Brand\Form as BrandForm;
use App\Http\Livewire\Category\Index as CategoryIndex;
use App\Http\Livewire\Category\Form as CategoryForm;

// Rute untuk Admin
Route::middleware(['web', 'ensure.auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Transaction Routes
    Route::get('/transactions/create', TransactionCreate::class)->name('transaction.create');
    Route::get('/riwayat-transaksi', TransactionHistory::class)->name('transaction.history');
    Route::get('/detail-transaksi/{id}', TransactionDetail::class)->name('transaction.detail');
    Route::get('/invoice/{id}', Invoice::class)->name('transaction.invoice');
    Route::get('/mini-invoice/{id}', MiniInvoice::class)->name('transaction.mini-invoice');
    
    // Delivery Routes
    Route::get('/pengiriman-barang', DeliveryIndex::class)->name('delivery.index');
    Route::get('/pengiriman-barang/{id}', DeliveryDetail::class)->name('delivery.detail');
    
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
    
    // Debt Routes
    Route::get('data-hutang', DebtIndex::class)->name('debt.index');
    
    // Brand Routes
    Route::get('data-brand', BrandIndex::class)->name('brand.index');
    Route::get('brand-baru', BrandForm::class)->name('brand.create');
    Route::get('ubah-brand/{id}', BrandForm::class)->name('brand.update');
    
    // Category Routes
    Route::get('data-kategori', CategoryIndex::class)->name('category.index');
    Route::get('kategori-baru', CategoryForm::class)->name('category.create');
    Route::get('ubah-kategori/{id}', CategoryForm::class)->name('category.update');
});
