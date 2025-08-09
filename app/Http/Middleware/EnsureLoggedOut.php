<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureLoggedOut
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures that when a user logs out from React,
     * they are also logged out from all Laravel guards and sessions.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a logout request or session invalidation
        if ($request->routeIs('logout') || $request->has('_logout')) {
            // Force logout from all guards
            Auth::guard('web')->logout();
            Auth::guard('customer')->logout();
            
            // Clear session completely
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login');
        }

        return $next($request);
    }
}
