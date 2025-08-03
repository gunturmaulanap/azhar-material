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
            
            // Stock management
            'edit-stock',
            'view-stock-reports',
            
            // Delivery management
            'view-deliveries',
            'manage-deliveries',
            
            // Debt management
            'view-debts',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - has all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin - operational work: transactions, orders, goods, delivery
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo([
            'view-dashboard',
            'view-products',
            'create-product',
            'edit-product',
            'view-transactions',
            'create-transaction',
            'edit-transaction',
            'view-orders',
            'create-order',
            'edit-order',
            'view-deliveries',
            'manage-deliveries',
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

        // Owner - reports, dashboard, and goods stock management only
        $ownerRole = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $ownerRole->givePermissionTo([
            'view-dashboard',
            'view-reports',
            'export-reports',
            'view-products',
            'edit-stock',
            'view-stock-reports',
        ]);

        // Assign roles to existing users
        $users = [
            'superadmin' => 'super_admin',
            'admin' => 'admin',
            'contentadmin' => 'content-admin',
            'guntur' => 'owner',
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
