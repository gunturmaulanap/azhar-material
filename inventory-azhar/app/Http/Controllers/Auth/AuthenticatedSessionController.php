<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
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

        $loginType = $request->input('login_type');

        if ($loginType === 'customer') {

            if (Auth::guard('customer')->attempt($request->only('username', 'password'))) {
                // Regenerasi session setelah login berhasil
                $request->session()->regenerate();

                // Ambil ID pelanggan yang berhasil login
                $customerId = Auth::guard('customer')->user()->id;

                // Redirect ke halaman dengan ID customer
                return redirect()->route('customer.detail', ['id' => $customerId]);
            }
        } else {
            if (Auth::guard('web')->attempt($request->only('username', 'password'))) {
                $request->session()->regenerate();
                return redirect(RouteServiceProvider::HOME);
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
}
