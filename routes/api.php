<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
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

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/visits', [VisitorController::class, 'store']);
Route::get('/analytics/snapshot', [VisitorController::class, 'snapshot']);

// === CSRF ===
// NOTE: idealnya pakai route Sanctum bawaan. Kalau mau custom, pastikan set cookie 'XSRF-TOKEN'.
Route::get('/sanctum/csrf-cookie', function () {
    // 120 menit, path '/', domain ikut config session, secure dan samesite ikut config
    Cookie::queue(cookie()->make(
        'XSRF-TOKEN',
        csrf_token(),
        120,
        '/',
        config('session.domain'),
        config('session.secure'),
        false,
        false,
        config('session.same_site', 'lax')
    ));

    return response()->json(['ok' => true])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
        ->header('Pragma', 'no-cache');
})->middleware('web');

// Public auth
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/{id}/image', [ProductController::class, 'image']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [ProductController::class, 'categories']);

// Brands
Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/active', [BrandController::class, 'active']);
Route::get('/brands/{id}', [BrandController::class, 'show']);

// Hero sections
Route::get('/hero-sections', [HeroSectionController::class, 'index']);
Route::get('/hero-sections/active', [HeroSectionController::class, 'active']);
Route::get('/hero-sections/{id}', [HeroSectionController::class, 'show']);

// Projects (PUBLIK)
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/active', [ProjectController::class, 'active']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);

// Teams
Route::get('/teams', [TeamController::class, 'index']);
Route::get('/teams/{id}', [TeamController::class, 'show']);

// Services
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

// About
Route::get('/about', [AboutController::class, 'index']);
Route::get('/about/{id}', [AboutController::class, 'show']);

// Contact
Route::post('/contact', [ContactController::class, 'send']);

// User check (session-based) → wajib 'web' supaya cookie session kebaca
Route::get('/user', [AuthController::class, 'user'])->middleware('web');

// Protected (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/verify', [AuthController::class, 'verify']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::put('/brands/{id}/content', [BrandController::class, 'updateContent']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
    Route::post('/about', [AboutController::class, 'store']);
    Route::put('/about/{id}', [AboutController::class, 'update']);
    Route::delete('/about/{id}', [AboutController::class, 'destroy']);
});

// Health
Route::get('/health', fn() =>
response()->json(['status' => 'ok', 'time' => now()->toISOString()]));
