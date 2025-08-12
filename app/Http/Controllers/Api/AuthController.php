<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function csrf()
    {
        return response()->json([
            'csrf_token' => csrf_token(),
        ])->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Vary' => 'Cookie, Authorization',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
            'login_type' => 'required|string|in:customer,user',
            'role' => 'nullable|string',
        ]);

        $guard = ($request->login_type === 'customer') ? 'customer' : 'web';
        $credentials = $request->only('username', 'password');

        $user = $guard === 'customer'
            ? Customer::where('username', $credentials['username'])->first()
            : User::where('username', $credentials['username'])->first();

        if (!$user) {
            return response()->json(['success' => false, 'error' => 'Username tidak ditemukan.'], 401);
        }
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['success' => false, 'error' => 'Password salah.'], 401);
        }

        if ($guard === 'web' && isset($request->role) && $user->role !== $request->role) {
            return response()->json(['success' => false, 'error' => 'Role yang digunakan salah.'], 401);
        }

        // Login via guard to bind to session
        Auth::guard($guard)->login($user, true);

        // Regenerate session id to avoid fixation
        if ($request->hasSession()) {
            $request->session()->regenerate();
            $request->session()->put('auth_guard', $guard);
        }

        // Clear old tokens and create a new token (server-side only)
        if (method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }
        $token = $user->createToken('auth-token', ['*'])->plainTextToken;

        $redirectUrl = $this->getRedirectUrl($user);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'role' => ($user instanceof Customer) ? 'customer' : $user->role,
                'redirect_url' => $redirectUrl,
            ],
            'token' => $token,
        ])->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Vary' => 'Cookie, Authorization',
        ]);
    }

    protected function getRedirectUrl($user): string
    {
        if ($user instanceof Customer) {
            return route('customer.dashboard', ['id' => $user->id]);
        }
        switch ($user->role) {
            case 'super_admin':
                return route('superadmin.dashboard');
            case 'admin':
                return route('admin.transaction.create');
            case 'content-admin':
                return route('content-admin.hero-sections');
            case 'owner':
                return route('owner.report.index');
            case 'driver':
                return route('driver.delivery.index');
            default:
                return url('/');
        }
    }

    public function register(Request $request)
    {
        // ... kode register ...
    }

    public function logout(Request $request)
    {
        try {
            // Delete Sanctum token if present
            $rawToken = $request->bearerToken() ?: $request->cookie('token');
            if ($rawToken) {
                $accessToken = PersonalAccessToken::findToken($rawToken);
                if ($accessToken) {
                    $accessToken->delete();
                }
            }

            // Logout session guards
            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                Auth::guard('web')->logout();
                if (method_exists($user, 'tokens')) {
                    $user->tokens()->delete();
                }
            }
            if (Auth::guard('customer')->check()) {
                $user = Auth::guard('customer')->user();
                Auth::guard('customer')->logout();
                if (method_exists($user, 'tokens')) {
                    $user->tokens()->delete();
                }
            }

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            $response = response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
            ])->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Vary' => 'Cookie, Authorization',
            ]);

            // Ensure cookies are cleared on client
            $response->headers->clearCookie('laravel_session');
            $response->headers->clearCookie('XSRF-TOKEN');

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function user(Request $request)
    {
        // Try session guards
        $user = Auth::guard('web')->user() ?? Auth::guard('customer')->user();

        // If missing, fallback to Sanctum token
        if (!$user) {
            $rawToken = $request->bearerToken() ?: $request->cookie('token');
            if ($rawToken) {
                $accessToken = PersonalAccessToken::findToken($rawToken);
                if ($accessToken) {
                    $user = $accessToken->tokenable;
                    if ($user) {
                        Auth::setUser($user);
                    }
                }
            }
        }

        if (!$user) {
            return response()->json([
                'user' => null,
            ])->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Vary' => 'Cookie, Authorization',
            ]);
        }

        return response()->json([
            'user' => $user,
        ])->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Vary' => 'Cookie, Authorization',
        ]);
    }

    public function verify(Request $request)
    {
        if ($request->user()) {
            return response()->json([
                'success' => true,
                'message' => 'Token valid',
                'data' => [
                    'user' => $request->user(),
                ],
            ])->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Vary' => 'Cookie, Authorization',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Token tidak valid atau kadaluarsa.'
        ], 401)->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Vary' => 'Cookie, Authorization',
        ]);
    }
}
