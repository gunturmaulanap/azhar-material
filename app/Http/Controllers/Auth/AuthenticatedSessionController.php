<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan tampilan login atau alihkan jika sudah terautentikasi.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(): View|RedirectResponse
    {
        // Jika sudah login dengan guard 'web' atau 'customer', alihkan ke homepage
        // User bisa akses dashboard melalui button dashboard di React SPA
        if (Auth::guard('web')->check() || Auth::guard('customer')->check()) {
            return redirect('/');
        }

        // Jika tidak ada yang terautentikasi, tampilkan halaman login React
        return view('react');
    }

    /**
     * Tangani permintaan otentikasi masuk.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // If SPA requests JSON, return JSON without redirect
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
            ])->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
            ]);
        }

        // Untuk non-AJAX, redirect ke halaman utama
        return redirect('/');
    }

    /**
     * Hancurkan sesi otentikasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        // Cek guard mana yang digunakan untuk logout
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } elseif (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
        }

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // If SPA requests JSON, return JSON without redirect
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
            ])->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
            ]);
        }

        // Pastikan semua cookies authentication terhapus
        $response = redirect('/');

        // Clear authentication cookies manually
        $response->withCookie(\Cookie::forget('laravel_session'));
        $response->withCookie(\Cookie::forget('XSRF-TOKEN'));

        return $response;
    }

    /**
     * Mengarahkan pengguna yang terautentikasi ke dashboard yang benar.
     *
     * @param  \App\Models\User|\App\Models\Customer  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        // Jika peran 'customer', alihkan ke dashboard customer
        if ($user instanceof Customer) {
            if (Route::has('customer.dashboard')) {
                return redirect()->route('customer.dashboard', ['id' => $user->id]);
            }
        }

        // Jika peran 'user' (admin, super_admin, dll.), alihkan berdasarkan perannya
        switch ($user->role) {
            case 'super_admin':
                if (Route::has('superadmin.dashboard')) {
                    return redirect()->route('superadmin.dashboard');
                }
                break;
            case 'admin':
                if (Route::has('admin.transaction.create')) {
                    return redirect()->route('admin.transaction.create');
                }
                break;
            case 'content-admin':
                if (Route::has('content-admin.hero-sections')) {
                    return redirect()->route('content-admin.hero-sections');
                }
                break;
            case 'owner':
                if (Route::has('owner.report.index')) {
                    return redirect()->route('owner.report.index');
                }
                break;
            case 'driver':
                if (Route::has('driver.delivery.index')) {
                    return redirect()->route('driver.delivery.index');
                }
                break;
        }

        // Fallback yang aman
        return redirect(url('/'));
    }
}
