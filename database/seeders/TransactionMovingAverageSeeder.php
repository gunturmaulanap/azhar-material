<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\GoodsTransaction;
use App\Models\Goods;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;

class TransactionMovingAverageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil user dan customer untuk transaksi
        $admin = User::where('role', 'admin')->first();
        $customer = Customer::first();
        
        // Ambil beberapa goods untuk testing moving average
        $semenMu380 = Goods::where('name', 'Semen MU 380')->first();
        $semenMu200 = Goods::where('name', 'Semen MU 200')->first();
        $semenMu400 = Goods::where('name', 'Semen MU 400')->first();
        
        if (!$admin || !$customer || !$semenMu380 || !$semenMu200 || !$semenMu400) {
            $this->command->error('Required data not found. Please run other seeders first.');
            return;
        }

        $this->command->info('Creating transactions for moving average chart...');

        // Buat data transaksi untuk 30 hari terakhir
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        
        $currentDate = $startDate->copy();
        $transactionId = 3; // Start from ID 3 since we already have 2 transactions

        while ($currentDate <= $endDate) {
            // Skip beberapa hari secara random untuk membuat pola yang lebih realistis
            if (rand(1, 10) <= 7) { // 70% chance untuk membuat transaksi di hari ini
                
                // Buat 1-3 transaksi per hari
                $transactionsPerDay = rand(1, 3);
                
                for ($i = 0; $i < $transactionsPerDay; $i++) {
                    // Random time dalam hari itu
                    $transactionTime = $currentDate->copy()->addHours(rand(8, 18))->addMinutes(rand(0, 59));
                    
                    // Create transaction
                    $transaction = Transaction::create([
                        'id' => $transactionId++,
                        'user_id' => $admin->id,
                        'customer_id' => $customer->id,
                        'name' => $customer->name ?? 'Test Customer',
                        'address' => $customer->address ?? 'Test Address',
                        'phone' => $customer->phone ?? '08123456789',
                        'total' => 0, // Will be calculated after goods are added
                        'discount' => 0,
                        'grand_total' => 0, // Will be calculated after goods are added
                        'balance' => 0,
                        'bill' => 0,
                        'return' => 0,
                        'status' => 'completed',
                        'created_at' => $transactionTime,
                        'updated_at' => $transactionTime,
                    ]);

                    // Random goods untuk transaksi ini
                    $goodsToSell = collect([$semenMu380, $semenMu200, $semenMu400])
                        ->random(rand(1, 2)); // Pilih 1-2 goods secara random

                    if (!$goodsToSell instanceof \Illuminate\Support\Collection) {
                        $goodsToSell = collect([$goodsToSell]);
                    }

                    foreach ($goodsToSell as $goods) {
                        // Quantity yang bervariasi untuk setiap goods
                        $qty = $this->generateRealisticQuantity($goods->name, $currentDate);
                        
                        GoodsTransaction::create([
                            'transaction_id' => $transaction->id,
                            'goods_id' => $goods->id,
                            'price' => $goods->price ?? 50000,
                            'qty' => $qty,
                            'subtotal' => $qty * ($goods->price ?? 50000),
                            'delivery' => 0,
                        ]);
                    }
                }
            }
            
            $currentDate->addDay();
        }

        $this->command->info('Successfully created ' . ($transactionId - 3) . ' additional transactions for moving average testing.');
    }

    /**
     * Generate realistic quantity based on goods type and date
     */
    private function generateRealisticQuantity($goodsName, $currentDate)
    {
        $baseQty = 1;
        
        // Semen biasanya dijual dalam quantity yang lebih besar
        if (str_contains($goodsName, 'Semen')) {
            $baseQty = rand(1, 50); // 1-50 bags
            
            // Weekend biasanya penjualan lebih sedikit
            if ($currentDate->isWeekend()) {
                $baseQty = max(1, intval($baseQty * 0.6));
            }
            
            // Akhir bulan biasanya penjualan lebih tinggi (proyek selesai)
            if ($currentDate->day >= 25) {
                $baseQty = intval($baseQty * 1.5);
            }
            
            // Awal bulan juga tinggi (proyek baru mulai)  
            if ($currentDate->day <= 5) {
                $baseQty = intval($baseQty * 1.3);
            }
        } else {
            $baseQty = rand(1, 10);
        }
        
        return max(1, $baseQty);
    }
}
