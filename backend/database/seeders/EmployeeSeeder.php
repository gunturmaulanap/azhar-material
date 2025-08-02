<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            ['name' => 'Andi Nugraha', 'phone' => '081234567890', 'position' => 'Manager', 'active' => true],
            ['name' => 'Siti Aisah', 'phone' => '085798765432', 'position' => 'Staff', 'active' => true],
            ['name' => 'Budi Santoso', 'phone' => '082112345678', 'position' => 'Supervisor', 'active' => false],
            ['name' => 'Ani Lestari', 'phone' => '083898765432', 'position' => 'HR', 'active' => true],
            ['name' => 'Rudi Hartono', 'phone' => '081912345678', 'position' => 'IT Support', 'active' => true],
            ['name' => 'Dinawati', 'phone' => '085398765432', 'position' => 'Finance', 'active' => false],
            ['name' => 'Chandra Kusuma', 'phone' => '082212345678', 'position' => 'Sales', 'active' => true],
            ['name' => 'Wulan Sari', 'phone' => '083998765432', 'position' => 'Marketing', 'active' => true],
            ['name' => 'Agus Prasetyo', 'phone' => '081112345678', 'position' => 'Staff', 'active' => false],
            ['name' => 'Dwi Lestari', 'phone' => '085598765432', 'position' => 'Customer Service', 'active' => true],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
