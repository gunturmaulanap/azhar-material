<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Support\RoutePicks;

class AuthenticatedSessionController extends Controller
{
    use RoutePicks;

    /**
     * Tampilkan tampilan login atau alihkan jika sudah terautentikasi.
     */
    public function create(): View|RedirectResponse
    {
        // Jika sudah login dengan guard 'web' atau 'customer', alihkan ke homepage (SPA)
        if (Auth::guard('web')->check() || Auth::guard('customer')->check()) {
            return redirect('/');
        }

        // Jika belum login, tampilkan halaman React
        return view('react');
    }

    /**
     * Tangani permintaan otentikasi masuk (form Laravel).
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Untuk SPA, cukup kembali ke halaman utama
        return redirect('/');
    }

    /**
     * Hancurkan sesi otentikasi.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } elseif (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
        }

        // Bersihkan session
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->regenerate(true);

        $resp   = redirect('/');
        $domain = config('session.domain'); // contoh: .azharmaterial.com
        $secure = (bool)config('session.secure');

        // Hapus cookies di beberapa kombinasi path/domain (aman di shared hosting)
        foreach (['/', null] as $path) {
            $resp->withCookie(\Cookie::forget('laravel_session', $path, $domain, null, $secure, false));
            $resp->withCookie(\Cookie::forget('XSRF-TOKEN',    $path, $domain, null, $secure, false));
        }
        // fallback tanpa domain
        $resp->withCookie(\Cookie::forget('laravel_session'));
        $resp->withCookie(\Cookie::forget('XSRF-TOKEN'));

        return $resp;
    }

    /**
     * Redirect pengguna ke dashboard yang sesuai peran.
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        if ($user instanceof \App\Models\Customer) {
            $name = $this->pickFirstExistingRoute([
                'customer.dashboard',
                'customer.index',
            ]);
            return $name ? redirect()->route($name, ['id' => $user->id]) : redirect('/');
        }

        $role = $user->role ?? 'customer';

        switch ($role) {
            case 'super_admin':
                $name = $this->pickFirstExistingRoute(['superadmin.dashboard']);
                break;

            case 'admin':
                $name = $this->pickFirstExistingRoute(['admin.transaction.create']);
                break;

            case 'content-admin':
                $name = $this->pickFirstExistingRoute([
                    'content-admin.analytics',
                    'content-admin.hero-sections',
                    'content-admin.hero-sections.index',
                ]);
                break;

            case 'owner':
                $name = $this->pickFirstExistingRoute(['owner.report.index']);
                break;

            case 'driver':
                $name = $this->pickFirstExistingRoute([
                    'admin.delivery.index',
                    'driver.delivery.index',
                ]);
                break;

            default:
                $name = null;
        }

        return $name ? redirect()->route($name) : redirect(url('/'));
    }
}
