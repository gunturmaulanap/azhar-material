<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function csrf()
    {
        return response()->json([
            'csrf_token' => csrf_token()
        ], 200)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username'   => 'required|string',
            'password'   => 'required|string',
            'login_type' => 'required|string|in:customer,user',
            'role'       => 'nullable|string',
        ]);

        $guard = $request->login_type === 'customer' ? 'customer' : 'web';
        $credentials = $request->only('username', 'password');

        // Ambil user dari guard yang sesuai
        $user = $guard === 'customer'
            ? Customer::where('username', $credentials['username'])->first()
            : User::where('username', $credentials['username'])->first();

        // Error detail (biar UX jelas)
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'Username tidak ditemukan.'], 401);
        }
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['success' => false, 'error' => 'Password salah.'], 401);
        }
        if ($guard === 'web' && $user->role !== $request->role) {
            return response()->json(['success' => false, 'error' => 'Role yang digunakan salah.'], 401);
        }

        // --- KUNCI BUG "login 2x": bersihkan sesi guard lain SEBELUM login baru ---
        // Jika sebelumnya login di guard berbeda, logout keduanya agar tidak bentrok
        try {
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }
            if (Auth::guard('customer')->check()) {
                Auth::guard('customer')->logout();
            }
        } catch (\Throwable $e) {
            // ignore
        }
        // Invalidate session lama + CSRF token lama
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Login guard yang dipilih & regenerate session id (anti fixation)
        Auth::guard($guard)->login($user, true);
        $request->session()->regenerate();
        // Simpan meta (opsional)
        $request->session()->put('auth_guard', $guard);
        $request->session()->put('user_authenticated_at', now());
        $request->session()->save();

        // Opsional: revoke token lama lalu buat token baru (kalau memang perlu)
        $token = null;
        if (method_exists($user, 'tokens')) {
            try {
                $user->tokens()->delete();
            } catch (\Throwable $e) {
            }
            try {
                $token = $user->createToken('auth-token', ['*'])->plainTextToken;
            } catch (\Throwable $e) {
            }
        }

        $redirectUrl = $this->getRedirectUrl($user);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data'    => [
                // kirim field yang aman saja
                'user'        => $this->briefUser($user),
                'role'        => $user instanceof Customer ? 'customer' : ($user->role ?? 'customer'),
                'redirect_url' => $redirectUrl,
            ],
            'token' => $token,
        ], 200)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
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
        // (tetapkan sesuai implementasi kamu)
        return response()->json([
            'success' => false,
            'message' => 'Register belum diimplementasikan.'
        ], 501);
    }

    public function logout(Request $request)
    {
        try {
            // Revoke tokens kalau ada
            try {
                $u = Auth::guard('web')->user() ?: Auth::guard('customer')->user();
                if ($u && method_exists($u, 'tokens')) $u->tokens()->delete();
            } catch (\Throwable $e) {
            }

            // Logout dari dua-duanya (aman jika salah satunya tidak aktif)
            try {
                Auth::guard('web')->logout();
            } catch (\Throwable $e) {
            }
            try {
                Auth::guard('customer')->logout();
            } catch (\Throwable $e) {
            }

            // Bersihkan sesi total
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ], 200)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function user(Request $request)
    {
        // Baca dari kedua guard; JANGAN return 401 di sini (bikin UI flicker)
        $user = Auth::guard('web')->user() ?: Auth::guard('customer')->user();

        return response()->json([
            'success' => true,
            'data' => [
                // kalau belum login -> null (tetap 200)
                'user' => $user ? $this->briefUser($user) : null,
            ],
        ], 200)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
    }

    public function verify(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'success' => (bool) $user,
            'message' => $user ? 'Token valid' : 'Token tidak valid atau kadaluarsa.',
            'data'    => ['user' => $user ? $this->briefUser($user) : null],
        ], $user ? 200 : 401)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    /** Hanya kirim field yang aman/terpakai ke frontend */
    protected function briefUser($user): array
    {
        $base = [
            'id'    => $user->id,
            'name'  => $user->name ?? ($user->username ?? null),
            'email' => $user->email ?? null,
        ];
        // role untuk User; Customer dianggap 'customer'
        $role = $user instanceof Customer ? 'customer' : ($user->role ?? 'customer');

        return array_merge($base, [
            'role'     => $role,
            'username' => $user->username ?? null,
        ]);
    }
}
