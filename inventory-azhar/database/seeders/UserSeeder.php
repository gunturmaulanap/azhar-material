<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $data = [
            [
                'name' => 'Azhar Material',
                'username' => 'superadmin',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Rina Andriani',
                'username' => 'admin_gudang',
                'role' => 'admin',
            ],
            [
                'name' => 'Budi Santoso',
                'username' => 'admin_penjualan',
                'role' => 'admin',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'username' => 'customer_sumatra',
                'role' => 'customer',
            ],
            [
                'name' => 'Eka Prasetyo',
                'username' => 'admin_keuangan',
                'role' => 'admin',
            ],
            [
                'name' => 'Andi Wijaya',
                'username' => 'customer_kalimantan',
                'role' => 'customer',
            ],
            [
                'name' => 'Maya Rahmawati',
                'username' => 'admin_it',
                'role' => 'admin',
            ],
            [
                'name' => 'Nurul Aini',
                'username' => 'customer_sulawesi',
                'role' => 'customer',
            ],
            [
                'name' => 'Doni Setiawan',
                'username' => 'admin_operasional',
                'role' => 'admin',
            ],
            [
                'name' => 'Fitri Amalia',
                'username' => 'customer_papua',
                'role' => 'customer',
            ],
            [
                'name' => 'Joko Prabowo',
                'username' => 'admin_hrd',
                'role' => 'admin',
            ],
            [
                'name' => 'Dewi Sartika',
                'username' => 'customer_bali',
                'role' => 'customer',
            ],
        ];

        foreach ($data as $user) {
            $address = '';

            if ($user['role'] == 'customer') {
                $address = $faker->address;
            }

            DB::table('users')->updateOrInsert([
                'name' => $user['name'],
                'username' => $user['username'],
                'address' => $address,

                'role' => $user['role'],
                'phone' => $this->generateRandomPhoneNumber(),
                'password' => Hash::make('password'),
                'created_at' => $this->generateRandomDate(),
                'updated_at' => now(),
            ]);
        }
    }

    private function generateRandomDate()
    {

        $start = Carbon::now()->subYears(1);
        $end = Carbon::now();

        return Carbon::createFromTimestamp(rand($start->timestamp, $end->timestamp));
    }

    private function generateRandomPhoneNumber()
    {
        // Membuat nomor telepon dengan awalan 08
        $phoneNumber = '08';

        // Menghasilkan 10 digit angka acak
        for ($i = 0; $i < 10; $i++) {
            $phoneNumber .= rand(0, 9);
        }

        return $phoneNumber;
    }
}
