<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Guest',
                'phone' => '',
                'address' => '',
                'balance' => 0,
            ],
            [
                'name' => 'Andi Nugraha',
                'phone' => '081234567890',
                'address' => 'Komplek Permata Hijau Blok B No. 12, RT 03 RW 05, Kelurahan Sukajadi, Kecamatan Bandung Wetan, Kota Bandung, Jawa Barat 40115, Indonesia',
                'member' => true
            ],
            [
                'name' => 'Siti Aisah',
                'phone' => '085798765432',
                'address' => 'Apartemen Thamrin City Tower 2, Lantai 15 Unit A, Jl. Jendral Sudirman Kav. 52-53, Jakarta Pusat 10220, Indonesia',
                'member' => true
            ],
            [
                'name' => 'Budi Santoso',
                'phone' => '082112345678',
                'address' => 'Desa Sumberagung, Kecamatan Banyumas, Kabupaten Banyumas, Jawa Tengah 53122, Indonesia',
                'member' => true,
                'username' => 'budi',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'Ani Lestari',
                'phone' => '083898765432',
                'address' => 'Perumahan Griya Asri Blok C No. 7, Jl. Raya Bogor Km 25, Bogor, Jawa Barat 16115, Indonesia',
                'member' => true
            ],
            [
                'name' => 'Rudi Hartono',
                'phone' => '081912345678',
                'address' => 'Gang Melati No. 3, Kelurahan Kenongo, Kecamatan Kota, Kota Blitar, Jawa Timur 66111, Indonesia',
                'member' => true,
                'username' => 'rudi',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'Dinawati',
                'phone' => '085398765432',
                'address' => 'Villa Nusa Indah Blok D No. 15, Jl. Raya Parahyangan, Bandung, Jawa Barat 40135, Indonesia',
                'member' => true
            ],
            [
                'name' => 'Chandra Kusuma',
                'phone' => '082212345678',
                'address' => 'Perumahan Taman Asri Blok E No. 9, Jl. A Yani, Makassar, Sulawesi Selatan 90231, Indonesia',
                'username' => 'chandra',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'Wulan Sari',
                'phone' => '083998765432',
                'address' => 'Desa Karanganyar, Kecamatan Ungaran, Kabupaten Semarang, Jawa Tengah 50222, Indonesia'
            ],
            [
                'name' => 'Agus Prasetyo',
                'phone' => '081112345678',
                'address' => 'Apartemen Menteng Park View Tower B, Lantai 20 Unit C, Jl. H.O.S. Cokroaminoto, Jakarta Pusat 10350, Indonesia',
                'username' => 'agus',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'Dwi Lestari',
                'phone' => '085598765432',
                'address' => 'Komplek Perumahan Griya Nusa Indah Blok A No. 5, Jl. Raya Denpasar, Badung, Bali 80361, Indonesia'
            ],
            [
                'name' => 'Putra',
                'phone' => '081234567891',
                'address' => 'Jalan Sudirman No. 123, Jakarta Pusat',
                'username' => 'putra',
                'password' => bcrypt('password'),
                'member' => true
            ]
        ];

        foreach ($data as $user) {
            $balance = 0;

            if (isset($user['member'])) {
                $balance = rand(1, 999) * 1000; // Menghasilkan kelipatan 1000 antara 1000 dan 999000
            }

            $customerData = [
                'name' => $user['name'],
                'phone' => $user['phone'],
                'address' => $user['address'],
                'balance' => $balance,
                'created_at' => $this->generateRandomDate(),
                'updated_at' => now(),
            ];
            
            // Tambahkan username dan password jika ada
            if (isset($user['username'])) {
                $customerData['username'] = $user['username'];
            }
            
            if (isset($user['password'])) {
                $customerData['password'] = $user['password'];
            }
            
            DB::table('customers')->updateOrInsert(['name' => $user['name']], $customerData);
        }
    }

    private function generateRandomDate()
    {

        $start = Carbon::now()->subYears(1);
        $end = Carbon::now();

        return Carbon::createFromTimestamp(rand($start->timestamp, $end->timestamp));
    }
}
