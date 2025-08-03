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
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Test authentication route
Route::post('/auth/test-login', function (Request $request) {
    $request->validate([
        'username' => 'required|string',
        'password' => 'required',
        'role' => 'required|string',
    ]);

    $user = User::where('username', $request->username)
                ->where('role', $request->role)
                ->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Username atau role tidak ditemukan'
        ], 404);
    }

    if (Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'role' => $user->role
            ]
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Password salah'
    ], 401);
});

// Product routes (public)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/categories', [ProductController::class, 'categories']);
Route::get('/brands', [ProductController::class, 'brands']);

// Hero Section routes (public)
Route::get('/hero-sections', [HeroSectionController::class, 'index']);
Route::get('/hero-sections/{id}', [HeroSectionController::class, 'show']);
Route::get('/hero-sections/active', [HeroSectionController::class, 'active']);

// Brand routes (public)
Route::get('/brands/{id}', [BrandController::class, 'show']);
Route::get('/brands/active', [BrandController::class, 'active']);

// Brand content management (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/brands/{id}/content', [BrandController::class, 'updateContent']);
});

// Team routes (public)
Route::get('/teams', [TeamController::class, 'index']);
Route::get('/teams/{id}', [TeamController::class, 'show']);

// Service routes (public)
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

// About routes (public)
Route::get('/about', [AboutController::class, 'index']);
Route::get('/about/{id}', [AboutController::class, 'show']);

// Service & About content management (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);

    Route::post('/about', [AboutController::class, 'store']);
    Route::put('/about/{id}', [AboutController::class, 'update']);
    Route::delete('/about/{id}', [AboutController::class, 'destroy']);
});

// Contact route (public)
Route::post('/contact', [ContactController::class, 'send']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
