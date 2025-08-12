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
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\VisitorController;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/visits', [VisitorController::class, 'store']);
Route::get('/analytics/snapshot', [VisitorController::class, 'snapshot']);
// Public routes
Route::get('/csrf-token', [AuthController::class, 'csrf']);

// Sanctum CSRF cookie route (for SPA authentication)
Route::get('/sanctum/csrf-cookie', [\Laravel\Sanctum\Http\Controllers\CsrfCookieController::class, 'show'])->middleware('web');

Route::post('/auth/login', [AuthController::class, 'login'])->middleware(['web','no-store']);
Route::post('/auth/register', [AuthController::class, 'register'])->middleware(['web','no-store']);

// Products API routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/{id}/image', [ProductController::class, 'image']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/categories', [ProductController::class, 'categories']);

// Brands API routes - Fixed the route ordering
Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/active', [BrandController::class, 'active']);
Route::get('/brands/{id}', [BrandController::class, 'show']);

// Hero sections API routes
Route::get('/hero-sections', [HeroSectionController::class, 'index']);
Route::get('/hero-sections/active', [HeroSectionController::class, 'active']);
Route::get('/hero-sections/{id}', [HeroSectionController::class, 'show']);

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/active', [ProjectController::class, 'active']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);

// Teams API routes  
Route::get('/teams', [TeamController::class, 'index']);
Route::get('/teams/{id}', [TeamController::class, 'show']);

// Services API routes
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

// About API routes
Route::get('/about', [AboutController::class, 'index']);
Route::get('/about/{id}', [AboutController::class, 'show']);

// Contact routes
Route::post('/contact', [ContactController::class, 'send']);

// User check endpoint - uses web middleware for session-based auth
Route::get('/user', [AuthController::class, 'user'])->middleware(['web','no-store']);
Route::get('/me', [AuthController::class, 'user'])->middleware(['web','no-store']);

// Protected routes (memerlukan token Sanctum)
Route::middleware(['web','auth:sanctum','no-store'])->group(function () {
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

// Health check / simple ping for debugging
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'time' => now()->toISOString()]);
});
