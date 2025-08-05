<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\RedirectResponse;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Dapatkan pengguna yang terautentikasi, baik dari guard 'web' maupun 'customer'.
        // Jika tidak ada yang terautentikasi, alihkan ke halaman login.
        $user = Auth::guard('web')->user() ?? Auth::guard('customer')->user();

        if (!$user) {
            return redirect(url('/login'));
        }

        // 2. Periksa apakah peran pengguna termasuk dalam peran yang diizinkan untuk rute ini.
        // Logika ini sekarang berlaku untuk semua pengguna yang terautentikasi, 
        // termasuk 'customer' jika rute yang diakses memang memerlukan peran 'customer'.
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Jika peran tidak cocok, alihkan pengguna ke dashboard yang sesuai.
        // Ini berfungsi sebagai fallback yang aman.
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Mengarahkan pengguna ke dashboard yang benar berdasarkan perannya.
     *
     * @param  \App\Models\User|\App\Models\Customer  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        switch ($user->role) {
            case 'super_admin':
                // Cek rute dashboard super admin
                if (Route::has('superadmin.dashboard')) {
                    return redirect()->route('superadmin.dashboard');
                }
                break;
            case 'admin':
                // Cek rute dashboard admin
                if (Route::has('admin.transaction.create')) {
                    return redirect()->route('admin.transaction.create');
                }
                break;
            case 'content-admin':
                // Cek rute dashboard content admin
                if (Route::has('content.hero-sections')) {
                    return redirect()->route('content.hero-sections');
                }
                break;
            case 'owner':
                // Cek rute dashboard owner
                if (Route::has('owner.report.index')) {
                    return redirect()->route('owner.report.index');
                }
            case 'driver':
                // Cek rute dashboard driver
                if (Route::has('driver.delivery.index')) {
                    return redirect()->route('driver.delivery.index');
                }
                break;
            case 'customer':
                // Cek rute dashboard customer
                if (Route::has('customer.dashboard')) {
                    return redirect()->route('customer.dashboard', ['id' => $user->id]);
                }
                break;
        }

        // Fallback yang aman: jika tidak ada rute dashboard yang cocok, 
        // alihkan ke halaman utama.
        return redirect(url('/'));
    }
}
