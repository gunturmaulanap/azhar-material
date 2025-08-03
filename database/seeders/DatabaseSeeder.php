<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,              // Create users first
            RolePermissionSeeder::class,    // Then create roles and assign them
            EmployeeSeeder::class,
            // AttendanceSeeder::class,
            SupplierSeeder::class,
            CustomerSeeder::class,
            // CustomerUserSeeder::class, // Menjalankan CustomerUserSeeder setelah CustomerSeeder
            GoodsSeeder::class,
            ContentSeeder::class,           // Content data (hero-section, brand, dll)
            TestValidationSeeder::class,    // Validate setup
        ]);
    }
}
