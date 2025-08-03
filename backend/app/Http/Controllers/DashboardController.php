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
        // Customer tidak lagi menggunakan dashboard, langsung ke detail page
        // Check if authenticated via customer guard
        if (auth()->guard('customer')->check()) {
            $customer = auth()->guard('customer')->user();
            return redirect()->route('customer.detail', ['id' => $customer->id]);
        }

        // Check if authenticated via web guard and has customer role
        if (auth()->guard('web')->check() && auth()->user()->role === 'customer') {
            $user = auth()->user();
            return redirect()->route('customer.detail', ['id' => $user->id]);
        }

        // Check if authenticated via web guard for admin roles
        if (auth()->guard('web')->check()) {
            $user = auth()->user();
            if (in_array($user->role, ['admin', 'super_admin', 'content-admin'])) {
                return view('dashboard');
            }
        }

        // If not authenticated or no valid role, redirect to login
        return redirect()->route('login')->withErrors(['auth' => 'Anda harus login terlebih dahulu.']);
    }
}
