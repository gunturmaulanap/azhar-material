<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan roles sudah ada
        $roles = ['super_admin', 'admin', 'content-admin'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

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

        foreach ($data as $userData) {
            $user = User::updateOrCreate(
                ['username' => $userData['username']],
                [
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'role' => $userData['role'],
                    'password' => Hash::make('password'),
                ]
            );

            // Assign Spatie role only for non-customer
            if ($userData['role'] !== 'customer') {
                $user->assignRole($userData['role']);
            }
        }
    }
}
