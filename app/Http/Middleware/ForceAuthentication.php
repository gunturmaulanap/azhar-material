<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ForceAuthentication
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        \Log::info('ForceAuthentication middleware triggered', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'required_roles' => $roles,
        ]);

        // Force session start jika belum
        if (!$request->hasSession()) {
            \Log::error('No session available');
            return $this->forceLogout($request);
        }

        if (!$request->session()->isStarted()) {
            $request->session()->start();
        }

        // Check standard Laravel Auth guards first
        $authUser = null;
        $userRole = null;
        
        if (Auth::guard('web')->check()) {
            $authUser = Auth::guard('web')->user();
            $userRole = $authUser->role;
        } elseif (Auth::guard('customer')->check()) {
            $authUser = Auth::guard('customer')->user();
            $userRole = 'customer';
        }
        
        // If Auth facade doesn't work, try manual session check
        if (!$authUser) {
            $userId = $request->session()->get('login_web_' . sha1('web'));
            $customerId = $request->session()->get('login_customer_' . sha1('customer'));

            \Log::info('Session check', [
                'user_id' => $userId,
                'customer_id' => $customerId,
                'session_id' => $request->session()->getId(),
            ]);

            // Manual user lookup from database
            if ($userId) {
                $user = DB::table('users')->where('id', $userId)->first();
                if ($user) {
                    $authUser = $user;
                    $userRole = $user->role;
                    \Log::info('User authenticated via database lookup', [
                        'user_id' => $user->id,
                        'role' => $user->role,
                    ]);
                }
            }

            if (!$authUser && $customerId) {
                $customer = DB::table('customers')->where('id', $customerId)->first();
                if ($customer) {
                    $authUser = $customer;
                    $userRole = 'customer';
                    \Log::info('Customer authenticated via database lookup', [
                        'customer_id' => $customer->id,
                    ]);
                }
            }
        }
        
        // Check if user role matches required roles
        if ($authUser && $userRole && in_array($userRole, $roles)) {
            \Log::info('Authentication successful', [
                'user_type' => isset($authUser->role) ? 'user' : 'customer',
                'user_id' => $authUser->id,
                'role' => $userRole,
            ]);
            return $next($request);
        }

        // If no valid authenticated user found
        \Log::warning('No valid authenticated user found, forcing logout', [
            'auth_user' => $authUser ? 'found' : 'not found',
            'user_role' => $userRole,
            'required_roles' => $roles,
        ]);
        return $this->forceLogout($request);
    }

    private function forceLogout(Request $request)
    {
        // Force clear all authentication
        Auth::guard('web')->logout();
        Auth::guard('customer')->logout();
        
        // Clear session completely
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->regenerate(true);

        \Log::info('Force logout completed');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');
    }
}
