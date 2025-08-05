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
        // Jika sudah login dengan guard 'web' atau 'customer', alihkan ke dashboard yang sesuai
        if (Auth::guard('web')->check()) {
            return $this->redirectBasedOnRole(Auth::guard('web')->user());
        }

        if (Auth::guard('customer')->check()) {
            return $this->redirectBasedOnRole(Auth::guard('customer')->user());
        }

        // Jika tidak ada yang terautentikasi, tampilkan halaman login
        return view('auth.login');
    }

    /**
     * Tangani permintaan otentikasi masuk.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Dapatkan user yang terautentikasi
        $user = Auth::guard($request->getLoginGuard())->user();

        // Alihkan berdasarkan peran
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Hancurkan sesi otentikasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Cek guard mana yang digunakan untuk logout
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } elseif (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
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
                if (Route::has('content.hero-sections')) {
                    return redirect()->route('content.hero-sections');
                }
                break;
            case 'owner':
                if (Route::has('owner.report.index')) {
                    return redirect()->route('owner.report.index');
                }
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
