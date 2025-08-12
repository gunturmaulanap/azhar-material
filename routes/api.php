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
|
| Catatan:
| - Untuk SPA Sanctum (cookie-based), semua endpoint yang
|   MEN-SET atau MEMBACA session/cookie harus lewat middleware "web".
| - Endpoint proteksi cukup pakai auth:sanctum (EnsureFrontendRequestsAreStateful
|   sudah terpasang di group "api" oleh Sanctum).
|
*/

// ----------------------
// Public / non-auth
// ----------------------
Route::post('/visits', [VisitorController::class, 'store'])->middleware('throttle:60,1');
Route::get('/analytics/snapshot', [VisitorController::class, 'snapshot'])->middleware('throttle:60,1');

// Produk / konten publik
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/{id}/image', [ProductController::class, 'image']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [ProductController::class, 'categories']);

Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/active', [BrandController::class, 'active']);
Route::get('/brands/{id}', [BrandController::class, 'show']);

Route::get('/hero-sections', [HeroSectionController::class, 'index']);
Route::get('/hero-sections/active', [HeroSectionController::class, 'active']);
Route::get('/hero-sections/{id}', [HeroSectionController::class, 'show']);

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/active', [ProjectController::class, 'active']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);

Route::get('/teams', [TeamController::class, 'index']);
Route::get('/teams/{id}', [TeamController::class, 'show']);

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

Route::get('/about', [AboutController::class, 'index']);
Route::get('/about/{id}', [AboutController::class, 'show']);

Route::post('/contact', [ContactController::class, 'send'])->middleware('throttle:20,1');

// ----------------------
// Session / Sanctum bootstrap (WAJIB "web")
// ----------------------
Route::middleware('web')->group(function () {
    // CSRF cookie untuk SPA (boleh pakai default Sanctum, ini versi kustom yang set XSRF-TOKEN)
    Route::get('/sanctum/csrf-cookie', function () {
        Cookie::queue(cookie()->make(
            'XSRF-TOKEN',
            csrf_token(),
            120,
            '/',
            config('session.domain'),
            (bool) config('session.secure', true),
            false,
            false,
            config('session.same_site', 'lax')
        ));

        return response()->json(['ok' => true])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
    });

    // Auth publik (login/register) — via session cookie
    Route::post('/auth/login',  [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('/auth/register', [AuthController::class, 'register'])->middleware('throttle:10,1');

    // Status user berbasis session (dipakai saat refresh)
    Route::get('/user', function (Request $request) {
        $user = $request->user(); // dibaca dari session oleh guard web

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user ? $user->only(['id', 'name', 'email', 'role']) : null,
            ],
        ], 200)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
    });
});

// ----------------------
// Protected (auth:sanctum)
// ----------------------
Route::middleware(['web', 'auth:sanctum'])->group(function () {
    // verifikasi token/role dll (opsional)
    Route::post('/auth/verify', [AuthController::class, 'verify'])->middleware('throttle:60,1');

    // logout: hapus sesi + token
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('throttle:30,1');

    Route::put('/brands/{id}/content', [BrandController::class, 'updateContent']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
    Route::post('/about', [AboutController::class, 'store']);
    Route::put('/about/{id}', [AboutController::class, 'update']);
    Route::delete('/about/{id}', [AboutController::class, 'destroy']);
});

// ----------------------
// Healthcheck
// ----------------------
Route::get(
    '/health',
    fn() =>
    response()->json(['status' => 'ok', 'time' => now()->toISOString()])
)->middleware('throttle:60,1');
