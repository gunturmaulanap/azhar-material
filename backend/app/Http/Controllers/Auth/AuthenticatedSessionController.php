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
    public function create(): View
    {
        return view('auth.login');
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
                return redirect()->route('customer.dashboard');
            }
            // If customer auth fails, try user authentication as fallback
            if (Auth::guard('web')->attempt($request->only('username', 'password'))) {
                $request->session()->regenerate();
                $user = Auth::user();
                if ($user->role === 'content-admin') {
                    return redirect()->route('content.dashboard');
                } elseif ($user->role === 'admin' || $user->role === 'super_admin') {
                    return redirect()->route('dashboard');
                } else {
                    return redirect(RouteServiceProvider::HOME);
                }
            }
        } else {
            // Try user authentication first
            if (Auth::guard('web')->attempt($request->only('username', 'password'))) {
                $request->session()->regenerate();
                $user = Auth::user();
                if ($user->role === 'content-admin') {
                    return redirect()->route('content.dashboard');
                } elseif ($user->role === 'admin' || $user->role === 'super_admin') {
                    return redirect()->route('dashboard');
                } else {
                    return redirect(RouteServiceProvider::HOME);
                }
            }
            // If user auth fails, try customer authentication as fallback
            if (Auth::guard('customer')->attempt($request->only('username', 'password'))) {
                $request->session()->regenerate();
                return redirect()->route('customer.dashboard');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah. Silakan coba lagi.',
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Logout dari guard aktif
        if (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        // Hapus session sepenuhnya
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman login default
        return redirect('/');
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
                // Redirect customer back to React company profile
                return redirect('http://localhost:3000');
            case 'admin':
            case 'super_admin':
                return redirect()->route('dashboard');
            case 'content-admin':
                return redirect()->route('content.dashboard');
            default:
                return redirect('http://localhost:3000');
        }
    }
}
