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
            UserSeeder::class,
            EmployeeSeeder::class,
            // AttendanceSeeder::class,
            SupplierSeeder::class,
            CustomerSeeder::class,
            GoodsSeeder::class,
        ]);
    }
}
