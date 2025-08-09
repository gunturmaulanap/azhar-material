<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Delivery;
use Symfony\Component\HttpFoundation\Response;

class VerifyCustomerTransaction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $type = 'transaction'): Response
    {
        // Pastikan user sudah login sebagai customer
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login');
        }

        // Ambil ID dari route parameter
        $routeId = $request->route('id');
        $authenticatedCustomerId = Auth::guard('customer')->id();

        // Verifikasi berdasarkan tipe (transaction atau delivery)
        if ($type === 'transaction') {
            $transaction = Transaction::find($routeId);
            
            if (!$transaction || $transaction->customer_id != $authenticatedCustomerId) {
                abort(403, 'Unauthorized access to transaction data.');
            }
        } elseif ($type === 'delivery') {
            $delivery = Delivery::find($routeId);
            
            if (!$delivery) {
                abort(404, 'Delivery not found.');
            }
            
            // Cek apakah delivery ini milik customer yang sedang login
            $transaction = Transaction::find($delivery->transaction_id);
            if (!$transaction || $transaction->customer_id != $authenticatedCustomerId) {
                abort(403, 'Unauthorized access to delivery data.');
            }
        }

        return $next($request);
    }
}
