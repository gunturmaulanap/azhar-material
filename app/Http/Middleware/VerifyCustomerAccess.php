<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyCustomerAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login sebagai customer
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login');
        }

        // Ambil ID dari route parameter
        $routeId = $request->route('id');
        $authenticatedCustomerId = Auth::guard('customer')->id();

        // Verifikasi bahwa ID di route sesuai dengan ID customer yang login
        if ($routeId != $authenticatedCustomerId) {
            abort(403, 'Unauthorized access to customer data.');
        }

        return $next($request);
    }
}
