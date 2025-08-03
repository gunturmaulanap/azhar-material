<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    /**
     * Display the login view or redirect if already authenticated.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        // Jika sudah login, redirect ke halaman sesuai role
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            return redirect()->route('customer.detail', ['id' => $customer->id]);
        }
        if (Auth::guard('web')->check()) {
            $user = Auth::user();
            if ($user->role === 'customer') {
                return redirect()->route('customer.detail', ['id' => $user->id]);
            } elseif ($user->role === 'content-admin') {
                return redirect()->route('content.dashboard');
            } elseif ($user->role === 'admin' || $user->role === 'super_admin') {
                return redirect()->route('dashboard');
            }
        }
        // Jika belum login, redirect ke React login page (return View agar sesuai tipe return)
        return redirect()->away('http://localhost:3000/login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $loginType = $request->input('login_type', 'user');

        if ($loginType === 'customer') {
            // Try customer authentication first
            if (Auth::guard('customer')->attempt($request->only('username', 'password'))) {
                $request->session()->regenerate();
                $customer = Auth::guard('customer')->user();
                // Redirect ke detail customer hybrid
                return redirect()->route('customer.detail', ['id' => $customer->id]);
            } else {
                // Jika gagal login customer, redirect ke React login page dengan pesan error
                return redirect('http://localhost:3000/login')->with('toast', 'Username atau password customer salah.');
            }
        }
        // Try user authentication (web guard)
        if (Auth::guard('web')->attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'customer') {
                // Redirect ke detail customer hybrid
                return redirect()->route('customer.detail', ['id' => $user->id]);
            } elseif ($user->role === 'content-admin') {
                return redirect()->route('content.dashboard');
            } elseif ($user->role === 'admin' || $user->role === 'super_admin') {
                return redirect()->route('dashboard');
            } else {
                return redirect(RouteServiceProvider::HOME);
            }
        }
        // If all auth fails, redirect ke React login page dengan pesan error
        return redirect('http://localhost:3000/login')->with('toast', 'Username atau password salah.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Logout dari semua guard yang mungkin aktif
        if (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
        }
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        // Hapus session sepenuhnya
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman company profile (React landing page) setelah logout
        return redirect('http://localhost:3000/');
    }

    /**
     * SSO Login untuk redirect dari React
     */
    public function ssoLogin(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect('http://localhost:3000/login')->withErrors(['username' => 'User tidak ditemukan.']);
        }

        // Login user ke session Laravel
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect berdasarkan role
        switch ($user->role) {
            case 'customer':
                // Redirect ke detail customer hybrid
                return redirect()->route('customer.detail', ['id' => $user->id]);
            case 'admin':
            case 'super_admin':
            case 'owner':
                return redirect()->route('dashboard');
            case 'content-admin':
                return redirect()->route('content.dashboard');
            default:
                // Jika tidak dalam halaman yang seharusnya, redirect ke React
                return redirect('http://localhost:3000');
        }
    }
}
