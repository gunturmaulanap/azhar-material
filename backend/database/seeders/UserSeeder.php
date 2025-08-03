<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'role' => 'admin',
            ],
            [
                'name' => 'Content Admin',
                'username' => 'contentadmin',
                'role' => 'content-admin',
            ],
            [
                'name' => 'Guntur Maulana',
                'username' => 'guntur',
                'role' => 'customer',
            ],
        ];

        foreach ($data as $user) {
            DB::table('users')->updateOrInsert(
                ['username' => $user['username']],
                [
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'password' => Hash::make('password'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
