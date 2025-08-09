<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestValidationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🧪 Testing Role and Permission Setup...');

        // Test 1: Check if all roles exist
        $expectedRoles = ['super_admin', 'admin', 'content-admin', 'owner', 'driver'];
        foreach ($expectedRoles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $this->command->info("✅ Role '$roleName' exists");
            } else {
                $this->command->error("❌ Role '$roleName' missing");
            }
        }

        // Test 2: Check if all users exist and have roles
        $expectedUsers = [
            'superadmin' => 'super_admin',
            'admin' => 'admin',
            'contentadmin' => 'content-admin',
            'guntur' => 'owner',
            'driver' => 'driver'
        ];

        foreach ($expectedUsers as $username => $expectedRole) {
            $user = User::where('username', $username)->first();
            if ($user) {
                $this->command->info("✅ User '$username' exists");

                if ($user->hasRole($expectedRole)) {
                    $this->command->info("✅ User '$username' has role '$expectedRole'");
                } else {
                    $this->command->error("❌ User '$username' missing role '$expectedRole'");
                }

                if ($user->role === $expectedRole) {
                    $this->command->info("✅ User '$username' has correct legacy role field");
                } else {
                    $this->command->error("❌ User '$username' legacy role mismatch");
                }
            } else {
                $this->command->error("❌ User '$username' missing");
            }
        }

        $this->command->info('🎉 Validation completed!');
    }
}
