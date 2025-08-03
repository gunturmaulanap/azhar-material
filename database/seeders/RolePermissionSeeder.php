<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard permissions
            'view-dashboard',
            'view-analytics',

            // User management
            'view-users',
            'create-user',
            'edit-user',
            'delete-user',

            // Customer management
            'view-customers',
            'create-customer',
            'edit-customer',
            'delete-customer',

            // Product/Goods management
            'view-products',
            'create-product',
            'edit-product',
            'delete-product',

            // Transaction management
            'view-transactions',
            'create-transaction',
            'edit-transaction',
            'delete-transaction',

            // Order management
            'view-orders',
            'create-order',
            'edit-order',
            'delete-order',

            // Supplier management
            'view-suppliers',
            'create-supplier',
            'edit-supplier',
            'delete-supplier',

            // Report access
            'view-reports',
            'export-reports',

            // Content management
            'manage-content',
            'edit-hero-sections',
            'edit-brands',
            'edit-services',
            'edit-about',
            'edit-teams',
            'view-visitors',

            // Employee management
            'view-employees',
            'create-employee',
            'edit-employee',
            'delete-employee',
            'manage-attendance',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - has all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin - has most permissions but not user management and some reports
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo([
            'view-dashboard',
            'view-customers',
            'create-customer',
            'edit-customer',
            'delete-customer',
            'view-products',
            'create-product',
            'edit-product',
            'delete-product',
            'view-transactions',
            'create-transaction',
            'edit-transaction',
            'delete-transaction',
            'view-orders',
            'create-order',
            'edit-order',
            'delete-order',
        ]);

        // Content Admin - only content management permissions
        $contentAdminRole = Role::firstOrCreate(['name' => 'content-admin', 'guard_name' => 'web']);
        $contentAdminRole->givePermissionTo([
            'view-dashboard',
            'manage-content',
            'edit-hero-sections',
            'edit-brands',
            'edit-services',
            'edit-about',
            'edit-teams',
            'view-visitors',
            'view-analytics',
        ]);

        // Owner - optional, similar to super admin but for business owner
        $ownerRole = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $ownerRole->givePermissionTo([
            'view-dashboard',
            'view-analytics',
            'view-users',
            'view-customers',
            'view-products',
            'view-transactions',
            'view-orders',
            'view-suppliers',
            'view-reports',
            'export-reports',
            'view-employees',
            'manage-attendance',
        ]);

        // Assign roles to existing users
        $users = [
            'miura' => 'super_admin',
            'admin' => 'admin',
            'contentadmin' => 'content-admin',
        ];

        foreach ($users as $username => $roleName) {
            $user = User::where('username', $username)->first();
            if ($user) {
                $user->assignRole($roleName);
                // Update role field untuk backward compatibility
                $user->update(['role' => $roleName]);
            }
        }
    }
}
