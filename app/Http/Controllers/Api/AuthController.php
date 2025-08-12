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

        // Login via guard untuk mengikat ke session hanya jika channelnya session-based (SPA pada domain yang sama)
        // Biarkan berjalan, StartSession ada di group 'web' pada route /api/user, tidak memaksa di sini
        Auth::guard($guard)->login($user, true);

        // Regenerate session jika ada (tidak wajib untuk API token-only)
        if ($request->hasSession()) {
            $request->session()->regenerate();
            $request->session()->put('auth_guard', $guard);
        }

        // Hapus token lama dan buat token Sanctum baru
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
            // Hapus token Sanctum berbasis bearer/cookie terlebih dahulu jika ada
            $rawToken = $request->bearerToken() ?: $request->cookie('token');
            if ($rawToken) {
                $accessToken = PersonalAccessToken::findToken($rawToken);
                if ($accessToken) {
                    $accessToken->delete();
                }
            }

            // Logout dari guard session jika ada
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

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function user(Request $request)
    {
        // Coba session guards
        $user = Auth::guard('web')->user() ?? Auth::guard('customer')->user();

        // Jika tidak ada, fallback ke Sanctum token
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
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
            ],
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
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Token tidak valid atau kadaluarsa.'
        ], 401);
    }
}
