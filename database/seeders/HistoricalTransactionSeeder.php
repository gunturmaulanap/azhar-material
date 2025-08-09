<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\GoodsTransaction;
use App\Models\Goods;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;

class HistoricalTransactionSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user dan customer untuk transaksi
        $admin = User::where('role', 'admin')->first();
        $customer = Customer::first();
        
        // Buat lebih banyak customer jika hanya ada satu
        if (Customer::count() < 5) {
            Customer::factory()->count(5)->create();
        }
        
        $customers = Customer::limit(5)->get();
        
        // Ambil semua goods yang ada
        $goods = Goods::all();
        
        // Identifikasi Semen Dynamix sebagai produk best seller
        $semenDynamix = Goods::where('name', 'like', '%dynamix%')
                           ->orWhere('name', 'like', '%Dynamix%')
                           ->first();
        
        if (!$admin || $customers->isEmpty() || $goods->isEmpty()) {
            echo "Required data not found. Please run other seeders first.\n";
            return;
        }

        echo "Creating historical transactions for 5 years (2020-2025)...\n";

        // Mulai dari 5 tahun yang lalu hingga sekarang
        $startDate = Carbon::now()->subYears(5)->startOfYear();
        $endDate = Carbon::now();
        
        $currentDate = $startDate->copy();
        $transactionId = Transaction::max('id') + 1 ?? 1;

        // Pola penjualan musiman
        $monthlyMultipliers = [
            1 => 0.8,   // Januari - setelah libur
            2 => 0.9,   // Februari - mulai meningkat
            3 => 1.2,   // Maret - mulai musim kering, banyak proyek
            4 => 1.3,   // April - puncak musim kering
            5 => 1.4,   // Mei - puncak penjualan semen
            6 => 1.3,   // Juni - masih tinggi
            7 => 1.1,   // Juli - sedikit turun
            8 => 1.0,   // Agustus - normal
            9 => 1.2,   // September - mulai naik lagi
            10 => 1.1,  // Oktober - stabil
            11 => 0.9,  // November - mulai turun
            12 => 0.7   // Desember - libur, proyek ditunda
        ];

        // Pola pertumbuhan tahunan (simulasi pertumbuhan bisnis)
        $yearlyGrowthRates = [
            2020 => 0.8,  // Tahun pandemi
            2021 => 0.9,  // Pemulihan
            2022 => 1.1,  // Growth
            2023 => 1.2,  // Ekspansi
            2024 => 1.3,  // Peak
            2025 => 1.35  // Continued growth
        ];
        
        // Semen Dynamix popularity growth per year (as best seller)
        $semenDynamixPopularity = [
            2020 => 0.15, // 15% chance in pandemic year
            2021 => 0.25, // 25% chance as market recovered
            2022 => 0.40, // 40% chance as brand gained trust
            2023 => 0.55, // 55% chance as it became popular
            2024 => 0.70, // 70% chance as it dominated market
            2025 => 0.80  // 80% chance as it became the top choice
        ];

        $totalTransactions = 0;

        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $month = $currentDate->month;
            
            // Skip tanggal di masa depan
            if ($currentDate > Carbon::now()) {
                break;
            }
            
            // Faktor musiman dan tahunan
            $seasonalMultiplier = $monthlyMultipliers[$month] ?? 1.0;
            $yearlyMultiplier = $yearlyGrowthRates[$year] ?? 1.0;
            $combinedMultiplier = $seasonalMultiplier * $yearlyMultiplier;
            
            // Weekend factor (weekend 50% lebih sedikit)
            $weekendFactor = $currentDate->isWeekend() ? 0.5 : 1.0;
            
            // Probabilitas ada transaksi (70% weekday, 35% weekend)
            $transactionProbability = $currentDate->isWeekend() ? 0.35 : 0.7;
            
            if (rand(1, 100) <= ($transactionProbability * 100)) {
                // Jumlah transaksi per hari (1-4, tergantung faktor)
                $transactionsPerDay = max(1, round((rand(1, 4) * $combinedMultiplier * $weekendFactor)));
                
                for ($i = 0; $i < $transactionsPerDay; $i++) {
                    // Random customer
                    $selectedCustomer = $customers->random();
                    
                    // Random time dalam hari itu (jam kerja 7-17)
                    $transactionTime = $currentDate->copy()
                        ->addHours(rand(7, 17))
                        ->addMinutes(rand(0, 59));
                    
                    // Create transaction
                    $transaction = Transaction::create([
                        'id' => $transactionId++,
                        'user_id' => $admin->id,
                        'customer_id' => $selectedCustomer->id,
                        'name' => $selectedCustomer->name ?? 'Historical Customer',
                        'address' => $selectedCustomer->address ?? 'Historical Address',
                        'phone' => $selectedCustomer->phone ?? '08123456789',
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

                    // Smart goods selection - favor Semen Dynamix based on year popularity
                    $dynamixPopularity = $semenDynamixPopularity[$year] ?? 0.15;
                    $goodsToSell = collect();
                    
                    // Always try to include Semen Dynamix first if available and based on popularity
                    if ($semenDynamix && (rand(1, 100) <= ($dynamixPopularity * 100))) {
                        $goodsToSell->push($semenDynamix);
                    }
                    
                    // Add 1-2 additional random goods if Dynamix was not selected or add more variety
                    $remainingGoodsCount = $goodsToSell->isEmpty() ? rand(1, 3) : rand(0, 2);
                    if ($remainingGoodsCount > 0) {
                        $otherGoods = $goods->except($semenDynamix ? $semenDynamix->id : 0)
                                          ->random(min($remainingGoodsCount, $goods->count() - ($semenDynamix ? 1 : 0)));
                        
                        if ($otherGoods instanceof \Illuminate\Database\Eloquent\Model) {
                            $goodsToSell->push($otherGoods);
                        } else {
                            $goodsToSell = $goodsToSell->merge($otherGoods);
                        }
                    }

                    $transactionTotal = 0;

                    foreach ($goodsToSell as $goodsItem) {
                        // Quantity yang bervariasi untuk setiap goods
                        // Give Semen Dynamix higher quantities as best seller
                        $isDynamix = $semenDynamix && $goodsItem->id === $semenDynamix->id;
                        $dynamixBonus = $isDynamix ? (1.2 + ($year - 2019) * 0.1) : 1.0; // 1.2x-1.8x bonus
                        
                        $qty = $this->generateRealisticQuantity(
                            $goodsItem->name, 
                            $currentDate, 
                            $combinedMultiplier * $dynamixBonus
                        );
                        
                        $price = $goodsItem->price ?? 50000;
                        $subtotal = $qty * $price;
                        $transactionTotal += $subtotal;
                        
                        GoodsTransaction::create([
                            'transaction_id' => $transaction->id,
                            'goods_id' => $goodsItem->id,
                            'price' => $price,
                            'qty' => $qty,
                            'subtotal' => $subtotal,
                            'delivery' => 0,
                        ]);
                    }
                    
                    // Update transaction totals
                    $discount = rand(0, min(5000, $transactionTotal * 0.05)); // 0-5% discount
                    $grandTotal = $transactionTotal - $discount;
                    
                    $transaction->update([
                        'total' => $transactionTotal,
                        'discount' => $discount,
                        'grand_total' => $grandTotal,
                        'bill' => $grandTotal,
                        'balance' => 0,
                        'return' => 0
                    ]);

                    $totalTransactions++;
                }
            }
            
            $currentDate->addDay();
        }

        // Calculate Semen Dynamix statistics
        $dynamixTransactions = 0;
        $totalDynamixQty = 0;
        
        if ($semenDynamix) {
            $dynamixStats = GoodsTransaction::where('goods_id', $semenDynamix->id)
                                         ->selectRaw('COUNT(*) as transactions, SUM(qty) as total_qty')
                                         ->first();
            $dynamixTransactions = $dynamixStats->transactions ?? 0;
            $totalDynamixQty = $dynamixStats->total_qty ?? 0;
        }
        
        echo "Successfully created {$totalTransactions} historical transactions spanning 5 years.\n";
        
        if ($semenDynamix) {
            echo "\nSemen Dynamix Best Seller Statistics:\n";
            echo "- Product: {$semenDynamix->name}\n";
            echo "- Price: Rp" . number_format($semenDynamix->price) . "/bag\n";
            echo "- Total transactions involving Dynamix: {$dynamixTransactions}\n";
            echo "- Total bags sold: {$totalDynamixQty}\n";
            echo "- Revenue from Dynamix: Rp" . number_format($totalDynamixQty * $semenDynamix->price) . "\n";
            
            $dynamixPercentage = $totalTransactions > 0 ? round(($dynamixTransactions / $totalTransactions) * 100, 1) : 0;
            echo "- Market share in transactions: {$dynamixPercentage}%\n";
            echo "\nDynamix popularity grew from 15% (2020) to 80% (2025) reflecting its best seller status!\n";
        }
    }

    /**
     * Generate realistic quantity based on goods type, date, and seasonal factors
     */
    private function generateRealisticQuantity($goodsName, $currentDate, $multiplier = 1.0)
    {
        $baseQty = 1;
        
        // Semen biasanya dijual dalam quantity yang lebih besar
        if (str_contains($goodsName, 'Semen') || str_contains($goodsName, 'semen')) {
            $baseQty = rand(5, 80); // 5-80 bags (lebih realistis)
            
            // Faktor musiman
            if (in_array($currentDate->month, [3, 4, 5, 6])) { // Musim kering
                $baseQty = intval($baseQty * 1.3);
            } elseif (in_array($currentDate->month, [12, 1])) { // Libur
                $baseQty = max(1, intval($baseQty * 0.6));
            }
            
        } elseif (str_contains($goodsName, 'Batu') || str_contains($goodsName, 'Pasir')) {
            $baseQty = rand(2, 20); // truk/kubik
            
        } elseif (str_contains($goodsName, 'Besi') || str_contains($goodsName, 'Baja')) {
            $baseQty = rand(1, 15); // batang/ton
            
        } else {
            $baseQty = rand(1, 25); // unit umum
        }
        
        // Apply multiplier
        $baseQty = max(1, intval($baseQty * $multiplier));
        
        // Weekend factor
        if ($currentDate->isWeekend()) {
            $baseQty = max(1, intval($baseQty * 0.7));
        }
        
        // Akhir/awal bulan factor (proyek deadline/mulai)
        if ($currentDate->day >= 25 || $currentDate->day <= 5) {
            $baseQty = intval($baseQty * 1.2);
        }
        
        return max(1, $baseQty);
    }
}
