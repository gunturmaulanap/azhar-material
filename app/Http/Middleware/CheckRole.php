<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\RedirectResponse;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Customer;

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
        // 1) Dapatkan user dari guard session terlebih dahulu
        $user = Auth::guard('web')->user() ?? Auth::guard('customer')->user();

        // 2) Jika belum ada, coba autentikasi via Sanctum token (Bearer token atau cookie 'token')
        if (!$user) {
            $bearerToken = $request->bearerToken();
            $cookieToken = $request->cookie('token');
            $rawToken = $bearerToken ?: $cookieToken;

            if ($rawToken) {
                $accessToken = PersonalAccessToken::findToken($rawToken);
                if ($accessToken) {
                    $tokenable = $accessToken->tokenable;
                    if ($tokenable) {
                        $user = $tokenable;
                        // Set user untuk lifecycle request ini agar Auth::user() bekerja
                        Auth::setUser($user);
                    }
                }
            }
        }

        // 3) Jika tetap tidak ada user, balas 401 untuk request JSON, atau redirect ke login untuk web
        if (!$user) {
            if ($request->expectsJson() || $request->ajax() || str_starts_with($request->path(), 'api/')) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect(route('login'));
        }

        // 4) Tentukan peran efektif (customer tidak memiliki properti role)
        $effectiveRole = ($user instanceof Customer) ? 'customer' : ($user->role ?? null);

        // 5) Jika rute mengizinkan role yang didaftarkan dan cocok, teruskan
        if ($effectiveRole !== null && in_array($effectiveRole, $roles, true)) {
            return $next($request);
        }

        // 6) Jika role tidak cocok, alihkan ke dashboard yang sesuai
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
        // Customer diarahkan ke dashboard customer
        if ($user instanceof Customer) {
            if (Route::has('customer.dashboard')) {
                return redirect()->route('customer.dashboard', ['id' => $user->id]);
            }
            return redirect(url('/'));
        }

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
                if (Route::has('content-admin.analytics')) {
                    return redirect()->route('content-admin.analytics');
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
