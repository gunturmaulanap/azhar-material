<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Goods;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function getSalesPercentageByCategory()
    {
        // Ambil semua transaksi dengan relasi barang dan kategori
        $transactions = Transaction::where('status', '!=', 'hutang')->get();

        // Inisialisasi array untuk menyimpan total penjualan per kategori
        $categorySales = [];

        // Hitung total penjualan per kategori
        foreach ($transactions as $transaction) {
            foreach ($transaction->goods as $good) {
                $categoryName = $good->category->name;
                $quantitySold = $good->pivot->qty; // Ambil qty dari pivot table

                if (!isset($categorySales[$categoryName])) {
                    $categorySales[$categoryName] = 0;
                }
                $categorySales[$categoryName] += $quantitySold;
            }
        }

        // Hitung total keseluruhan untuk menghitung persentase
        $totalSales = array_sum($categorySales);

        // Siapkan data untuk chart
        $chartData = [];
        foreach ($categorySales as $category => $quantity) {
            $chartData[] = [
                'name' => $category,
                'y' => ($totalSales > 0) ? ($quantity / $totalSales) * 100 : 0, // Hitung persentase
            ];
        }

        // Kembalikan data ke view atau format JSON
        return response()->json($chartData);
    }

    public function index()
    {
        // Double check authentication - force strict checking
        if (!Auth::guard('web')->check()) {
            \Log::warning('Unauthenticated access attempt to superadmin dashboard', [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl()
            ]);
            
            // Clear any existing session
            request()->session()->flush();
            request()->session()->regenerate();
            
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
        
        // Pastikan hanya user dengan role super_admin atau owner yang bisa akses
        $user = Auth::guard('web')->user();
        if (!$user || !in_array($user->role, ['super_admin', 'owner'])) {
            \Log::warning('Unauthorized role access attempt to dashboard', [
                'user_id' => $user ? $user->id : null,
                'role' => $user ? $user->role : 'none',
                'ip' => request()->ip(),
            ]);
            abort(403, 'Unauthorized access - insufficient privileges');
        }
        
        return view('dashboard');
    }
}
