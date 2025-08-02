<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $statuses = ['Hadir', 'Izin', 'Alpha'];

        // Rentang waktu dari dua minggu lalu hingga satu minggu ke depan
        $startDate = Carbon::now()->subWeeks(2)->startOfWeek(Carbon::MONDAY);
        $endDate = Carbon::now()->addWeeks(1)->endOfWeek(Carbon::SUNDAY);

        foreach ($employees as $employee) {
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $currentDate->toDateString(),
                    'status' => $statuses[array_rand($statuses)], // Status acak
                ]);

                // Lanjut ke hari berikutnya
                $currentDate->addDay();
            }
        }
    }
}
