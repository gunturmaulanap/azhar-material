<?php

namespace App\Http\Livewire\Attendace;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Attendance;

class Create extends Component
{
    public $attendanceId;
    public $attendanceEmployees = [], $activeEmployees;

    public function resetData()
    {
        $this->attendanceEmployees = [];
        $this->setData();
    }

    public function setData()
    {
        $employees = Employee::where('active', true)->get();

        foreach ($employees as $employee) {
            $this->attendanceEmployees[] = [
                'employee_id' => $employee->id,
                'name' => $employee->name,
                'position' => $employee->position,
                'absen' => false,
            ];
        }
    }

    public function save()
    {
        $now = Carbon::now('Asia/Jakarta');

        // Menghitung total karyawan aktif
        $activeTotal = $this->activeEmployees->count();

        // Mencari atau membuat data kehadiran
        $attendance = Attendance::updateOrCreate(
            ['id' => $this->attendanceId], // Ganti dengan ID yang sesuai jika ingin update
            [
                'absen_total' => 0,
                'absent_total' => 0,
                'active_total' => $activeTotal,
                'created_at' => $now,
            ]
        );

        // Reset total absen dan tidak absen
        $attendance->absen_total = 0;
        $attendance->absent_total = 0;

        // Menyimpan data kehadiran karyawan
        foreach ($this->attendanceEmployees as $employee) {
            // Memperbarui atau menambahkan data kehadiran
            $attendance->employees()->syncWithoutDetaching([
                $employee['employee_id'] => [
                    'absen' => $employee['absen'] ?? false,
                ],
            ]);

            // Hitung total absen dan tidak absen
            ($employee['absen'] ?? false) ? $attendance->absen_total++ : $attendance->absent_total++;
        }

        // Simpan perubahan
        $attendance->save();

        return redirect()->route('attendance.detail', ['id' => $attendance->id])->with('success', 'Kehadiran telah disimpan!');
    }


    public function mount($id = null)
    {
        $this->activeEmployees = Employee::where('active', true)->get();

        if (!$id) {
            $this->setData();
        } else {
            $this->attendanceId = $id;
            $attendance = Attendance::findOrFail($this->attendanceId);

            foreach ($attendance->employees as $employee) {
                $this->attendanceEmployees[] = [
                    'employee_id' => $employee->id,
                    'name' => $employee->name,
                    'position' => $employee->position,
                    'absen' => $employee->pivot->absen,
                ];
            }
        }
    }

    public function render()
    {
        return view('livewire.attendace.create');
    }
}
