<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('EnsureAuthenticated middleware triggered', [
            'url' => $request->fullUrl(),
            'session_started' => $request->hasSession() && $request->session()->isStarted(),
            'session_id' => $request->session()->getId(),
        ]);

        // Force check session status
        if (!$request->hasSession() || !$request->session()->isStarted()) {
            \Log::info('No valid session found');
            return $this->redirectToLogin($request);
        }

        // Check both web and customer guards with detailed logging
        $webUser = Auth::guard('web')->user();
        $customerUser = Auth::guard('customer')->user();
        
        \Log::info('Authentication check', [
            'web_user' => $webUser ? $webUser->id : null,
            'customer_user' => $customerUser ? $customerUser->id : null,
            'web_check' => Auth::guard('web')->check(),
            'customer_check' => Auth::guard('customer')->check(),
        ]);

        // If no user authenticated at all
        if (!$webUser && !$customerUser) {
            \Log::info('No authenticated user found, clearing session');
            // Clear any invalid session data
            if ($request->hasSession()) {
                $request->session()->flush();
                $request->session()->regenerate();
            }
            
            return $this->redirectToLogin($request);
        }

        // Double check with Auth::check() for additional security
        if ($webUser && !Auth::guard('web')->check()) {
            \Log::info('Web user found but not authenticated, logging out');
            Auth::guard('web')->logout();
            $request->session()->flush();
            return $this->redirectToLogin($request);
        }

        if ($customerUser && !Auth::guard('customer')->check()) {
            \Log::info('Customer user found but not authenticated, logging out');
            Auth::guard('customer')->logout();
            $request->session()->flush();
            return $this->redirectToLogin($request);
        }

        \Log::info('Authentication successful, proceeding');
        return $next($request);
    }

    /**
     * Redirect to login page
     */
    private function redirectToLogin(Request $request)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->route('login');
    }
}
