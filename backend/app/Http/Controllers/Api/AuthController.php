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
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
            'role' => 'required|string',
        ]);

        if ($request->role === 'customer') {
            $customer = \App\Models\Customer::where('username', $request->username)->first();
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'error' => 'Username customer tidak ditemukan.',
                ], 401);
            }
            if (!\Hash::check($request->password, $customer->password)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Password customer salah.',
                ], 401);
            }
            if (\Auth::guard('customer')->attempt(['username' => $request->username, 'password' => $request->password])) {
                $customer = \Auth::guard('customer')->user();
                $redirectUrl = url('/customer/' . $customer->id);
                return response()->json([
                    'success' => true,
                    'message' => 'Login customer berhasil',
                    'data' => [
                        'user' => $customer,
                        'redirectUrl' => $redirectUrl
                    ]
                ]);
            }
            return response()->json([
                'success' => false,
                'error' => 'Gagal login customer.',
            ], 401);
        } else {
            // Login untuk admin, super_admin, content-admin
            $user = \App\Models\User::where('username', $request->username)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Username tidak ditemukan.',
                ], 401);
            }
            if ($user->role !== $request->role) {
                return response()->json([
                    'success' => false,
                    'error' => 'Role tidak sesuai.',
                ], 401);
            }
            if (!\Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Password salah.',
                ], 401);
            }
            if (\Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password])) {
                $user = \Auth::user();
                $redirectUrl = 'http://localhost:3000/';
                if ($user->role === 'content-admin') {
                    $redirectUrl = 'http://localhost:8000/sso-login/' . $user->id;
                } elseif ($user->role === 'admin' || $user->role === 'super_admin') {
                    $redirectUrl = 'http://localhost:8000/sso-login/' . $user->id;
                }
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
            return response()->json([
                'success' => false,
                'error' => 'Gagal login user.',
            ], 401);
        }
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
