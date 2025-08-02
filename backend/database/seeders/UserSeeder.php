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
            // Demo accounts for testing
            [
                'name' => 'Customer Demo',
                'username' => 'customer',
                'role' => 'customer',
            ],
            [
                'name' => 'Admin Demo', 
                'username' => 'admin',
                'role' => 'admin',
            ],
            [
                'name' => 'Super Admin Demo',
                'username' => 'superadmin',
                'role' => 'superadmin',
            ],
            [
                'name' => 'Content Admin Demo',
                'username' => 'contentadmin',
                'role' => 'content-admin',
            ],
            // Original accounts
            [
                'name' => 'Azhar Material',
                'username' => 'azhar_superadmin',
                'role' => 'superadmin',
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
                'name' => 'Content Manager',
                'username' => 'content_manager',
                'role' => 'content-admin',
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
