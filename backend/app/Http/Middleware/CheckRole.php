<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        
        // If user is authenticated via customer guard, treat as customer role
        if (auth()->guard('customer')->check()) {
            if (in_array('customer', $roles)) {
                return $next($request);
            }
        }
        // If user is authenticated via web guard, check their role
        elseif (auth()->guard('web')->check() && isset($user->role)) {
            if (in_array($user->role, $roles)) {
                return $next($request);
            }
        }
        
        return back();
    }
}
