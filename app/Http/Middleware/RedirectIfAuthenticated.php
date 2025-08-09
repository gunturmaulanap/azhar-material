<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Tambahkan namespace Log
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // If request is for login page and user is authenticated, redirect to home
                if ($request->is('login') || $request->is('login/*')) {
                    return redirect('/');
                }
                
                // Default redirects for different guards
                switch ($guard) {
                    case 'customer':
                        $customer = Auth::guard('customer')->user();
                        if (Route::has('customer.dashboard')) {
                            return redirect()->route('customer.dashboard', ['id' => $customer->id]);
                        }
                        return redirect('/');
                    case 'web':
                        return redirect(RouteServiceProvider::HOME);
                    default:
                        return redirect('/');
                }
            }
        }
    
        return $next($request);
    }
}
