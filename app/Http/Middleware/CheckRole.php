<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;
use App\Support\RoutePicks;

class CheckRole
{
    use RoutePicks;

    /**
     * Pastikan role sesuai; jika tidak, redirect ke dashboard role yang benar.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response|RedirectResponse
    {
        // Ambil user dari kedua guard
        $user = Auth::guard('web')->user() ?? Auth::guard('customer')->user();

        if (!$user) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect(route('login'));
        }

        // Role efektif
        $effectiveRole = $user instanceof \App\Models\Customer
            ? 'customer'
            : ($user->role ?? 'customer');

        // Jika ada pembatasan role pada route dan tidak cocok -> redirect
        if (!empty($roles) && !in_array($effectiveRole, $roles, true)) {
            return $this->redirectBasedOnRole($user);
        }

        return $next($request);
    }

    protected function redirectBasedOnRole($user): RedirectResponse
    {
        $role = $user instanceof \App\Models\Customer ? 'customer' : ($user->role ?? 'customer');

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

            case 'customer':
                $name = $this->pickFirstExistingRoute(['customer.dashboard', 'customer.index']);
                if ($name) {
                    return redirect()->route($name, ['id' => $user->id]);
                }
                $name = null;
                break;

            default:
                $name = null;
        }

        return $name ? redirect()->route($name) : redirect(url('/'));
    }
}
