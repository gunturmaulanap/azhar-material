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
        // If user is authenticated via web guard, check their role using Spatie
        elseif (auth()->guard('web')->check() && $user) {
            // Use Spatie for role check if available
            if (method_exists($user, 'hasAnyRole')) {
                if ($user->hasAnyRole($roles)) {
                    return $next($request);
                }
            } else if (isset($user->role) && in_array($user->role, $roles)) {
                // Fallback to manual role property
                return $next($request);
            }
        }

        // Redirect to appropriate page if role doesn't match
        if (auth()->guard('customer')->check()) {
            $customer = auth()->guard('customer')->user();
            return redirect()->route('customer.detail', ['id' => $customer->id]);
        } elseif (auth()->guard('web')->check()) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }
}
