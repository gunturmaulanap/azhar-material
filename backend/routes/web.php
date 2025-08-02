<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\HeroSection\Index as HeroSectionIndex;
use App\Http\Livewire\Brand\Index as BrandIndex;

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

// Redirect root to company profile (React app)
Route::get('/', function () {
    return redirect('http://localhost:3000');
});

// Admin routes (Laravel Livewire)
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Hero Section Management
    Route::get('/hero-sections', HeroSectionIndex::class)->name('admin.hero-sections');

    // Brand Management
    Route::get('/brands', BrandIndex::class)->name('admin.brands');
});

// Auth routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

require __DIR__ . '/auth.php';
