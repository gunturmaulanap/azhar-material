<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\HeroSectionController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\AboutController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/csrf-token', [AuthController::class, 'csrf']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/categories', [ProductController::class, 'categories']);
Route::get('/brands', [ProductController::class, 'brands']);
Route::get('/hero-sections', [HeroSectionController::class, 'index']);
Route::get('/hero-sections/{id}', [HeroSectionController::class, 'show']);
Route::get('/hero-sections/active', [HeroSectionController::class, 'active']);
Route::get('/brands/{id}', [BrandController::class, 'show']);
Route::get('/brands/active', [BrandController::class, 'active']);
Route::get('/teams', [TeamController::class, 'index']);
Route::get('/teams/{id}', [TeamController::class, 'show']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/about/{id}', [AboutController::class, 'show']);
Route::post('/contact', [ContactController::class, 'send']);

// Protected routes (memerlukan token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Perbaikan: Rute /user sekarang tersedia
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/auth/verify', [AuthController::class, 'verify']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Rute manajemen konten yang dipindahkan ke sini
    Route::put('/brands/{id}/content', [BrandController::class, 'updateContent']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
    Route::post('/about', [AboutController::class, 'store']);
    Route::put('/about/{id}', [AboutController::class, 'update']);
    Route::delete('/about/{id}', [AboutController::class, 'destroy']);
});
