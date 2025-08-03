<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
            'role' => 'required|string',
        ]);

        // Cek apakah user ada dan role sesuai
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['Username tidak ditemukan.'],
            ]);
        }

        // Validasi role
        if ($user->role !== $request->role) {
            throw ValidationException::withMessages([
                'role' => ['Role yang dipilih tidak sesuai dengan akun Anda.'],
            ]);
        }

        // Coba login
        if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();

            // Tentukan redirect URL berdasarkan role
            $redirectUrl = 'http://localhost:3000/'; // Default to React app root
            if ($user->role === 'content-admin') {
                $redirectUrl = 'http://localhost:8000/sso-login/' . $user->id; // SSO ke Laravel content dashboard
            } elseif ($user->role === 'admin' || $user->role === 'super_admin') {
                $redirectUrl = 'http://localhost:8000/sso-login/' . $user->id; // SSO ke Laravel dashboard
            } elseif ($user->role === 'customer') {
                // Customer langsung ke Laravel dashboard tanpa SSO
                $redirectUrl = 'http://localhost:8000/customer/dashboard';
            }

            // Generate token untuk API authentication
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'redirectUrl' => $redirectUrl
                ]
            ]);
        }

        throw ValidationException::withMessages([
            'password' => ['Password salah.'],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }
}
