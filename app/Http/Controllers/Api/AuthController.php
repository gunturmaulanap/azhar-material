<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function csrf()
    {
        return response()->json([
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
            'login_type' => 'required|string|in:customer,user',
            'role' => 'nullable|string',
        ]);

        $guard = ($request->login_type === 'customer') ? 'customer' : 'web';
        $credentials = $request->only('username', 'password');

        // Pendekatan login manual untuk kontrol yang lebih baik
        if ($guard === 'customer') {
            $user = Customer::where('username', $credentials['username'])->first();
        } else {
            $user = User::where('username', $credentials['username'])->first();
        }

        // --- START: Perubahan di sini untuk pesan error lebih spesifik ---
        if (!$user) {
            // Jika username tidak ditemukan
            return response()->json(['success' => false, 'error' => 'Username tidak ditemukan.'], 401);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            // Jika password salah
            return response()->json(['success' => false, 'error' => 'Password salah.'], 401);
        }
        // --- END: Perubahan di sini ---

        // Jika kredensial valid, login pengguna untuk membuat sesi
        Auth::guard($guard)->login($user);

        // Jika login_type adalah 'user', pastikan role sesuai
        if ($guard === 'web' && $user->role !== $request->role) {
            // Logout user yang baru login karena role tidak cocok
            Auth::guard($guard)->logout();
            return response()->json(['success' => false, 'error' => 'Role yang digunakan salah.'], 401); // Pesan diubah sedikit
        }

        // Hapus token lama untuk keamanan
        $user->tokens()->delete();

        // Buat token Sanctum baru
        $token = $user->createToken('auth-token')->plainTextToken;

        // Tentukan URL pengalihan berdasarkan peran
        $redirectUrl = $this->getRedirectUrl($user);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'role' => ($user instanceof Customer) ? 'customer' : $user->role,
                'redirect_url' => $redirectUrl, // Tambahkan URL pengalihan
            ],
            'token' => $token,
        ]);
    }

    /**
     * Dapatkan URL pengalihan berdasarkan peran pengguna.
     *
     * @param \App\Models\User|\App\Models\Customer $user
     * @return string
     */
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
                return route('content.hero-sections');
            case 'owner':
                return route('owner.report.index');
            case 'driver':
                return route('drive.delivery.index');
            default:
                return url('/');
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // ... kode register tetap sama ...
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // ... kode logout tetap sama ...
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        // Perbaikan: Periksa guard secara eksplisit dan ambil user dari guard yang benar.
        // Ini memastikan bahwa kita mendapatkan user yang benar, baik itu 'web' atau 'customer'.
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
        } elseif (Auth::guard('customer')->check()) {
            $user = Auth::guard('customer')->user();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user
            ]
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        if ($request->user()) {
            return response()->json([
                'success' => true,
                'message' => 'Token valid',
                'data' => [
                    'user' => $request->user()
                ]
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Token tidak valid atau kadaluarsa.'
        ], 401);
    }
}
