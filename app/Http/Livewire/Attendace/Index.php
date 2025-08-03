<?php

namespace App\Http\Livewire\Attendace;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Attendance;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

class Index extends Component
{
    public $employees;
    public $weekDates = [];
    public $weeklyAttendance = [];
    public $selectedMonth, $selectedWeek;
    public $startDate, $endDate;

    public function getStartDate()
    {
        $startDate = Carbon::parse($this->selectedMonth . '-01');
        $startOfWeek = $startDate->startOfWeek();

        $startOfWeek->addWeeks($this->selectedWeek - 1);

        return $startOfWeek;
    }

    public function getEndDate()
    {
        $startDate = Carbon::parse($this->startDate);
        $endDate = $startDate->copy()->endOfWeek();
        return $endDate->translatedFormat('l, d M Y');
    }

    public function updated($property)
    {
        // Muat data ulang saat filter diubah
        if (in_array($property, ['selectedMonth', 'selectedWeek'])) {
            $this->loadWeeklyAttendance();
        }
    }

    public function updateAttendance($employeeId, $date, $status)
    {
        // Mencari data absensi berdasarkan employee_id dan tanggal
        $attendance = Attendance::firstOrCreate(
            ['employee_id' => $employeeId, 'date' => $date],
            ['status' => 'Alpha'] // Default ke 'Alpha' jika tidak ditemukan
        );

        // Mengupdate status absensi
        $attendance->status = $status;
        $attendance->save();

        // Muat ulang data absensi agar tampilan terbaru
        $this->loadWeeklyAttendance();
    }


    public function getWeekOfMonth($date)
    {
        return (int) ceil($date->day / 7 + 1);
    }

    public function mount()
    {
        // Mengambil semua pegawai yang statusnya aktif
        $this->employees = Employee::where('active', true)->get();

        // Mengatur nilai default bulan dan minggu yang sedang berlangsung
        $this->selectedMonth = Carbon::now()->format('Y-m');

        // Mengambil minggu ke berapa sekarang pada bulan ini
        $this->selectedWeek = $this->getWeekOfMonth(Carbon::now());

        // Memanggil fungsi untuk memuat absensi mingguan
        $this->loadWeeklyAttendance();

        // Menyimpan tanggal mulai minggu dan tanggal akhir minggu
        $this->startDate = $this->getStartDate();
        $this->endDate = $this->getEndDate();
    }


    public function initializeWeekDates()
    {
        // Membuat objek Carbon untuk tanggal pertama bulan yang dipilih
        $selectedDate = Carbon::createFromFormat('Y-m', $this->selectedMonth)->startOfMonth();

        // Menentukan tanggal mulai minggu berdasarkan minggu yang dipilih
        $startOfWeek = $selectedDate->copy()->addWeeks($this->selectedWeek - 1)->startOfWeek(Carbon::MONDAY);

        // Menginisialisasi array kosong untuk menyimpan tanggal-tanggal dalam minggu
        $this->weekDates = [];

        // Mengisi array dengan tanggal-tanggal dalam minggu yang dipilih
        for ($i = 0; $i < 7; $i++) {
            $this->weekDates[] = $startOfWeek->copy()->addDays($i)->toDateString();
        }
    }


    public function loadWeeklyAttendance()
    {
        // Memanggil fungsi initializeWeekDates untuk menghitung tanggal-tanggal dalam minggu
        $this->initializeWeekDates();

        // Menginisialisasi array kosong untuk menyimpan data absensi pegawai
        $this->weeklyAttendance = [];

        // Looping melalui setiap pegawai untuk mendapatkan data absensi mereka
        foreach ($this->employees as $employee) {
            $attendanceByDay = [];

            // Looping melalui setiap tanggal dalam minggu
            foreach ($this->weekDates as $date) {
                // Mengambil data absensi untuk pegawai dan tanggal tertentu
                $attendanceRecord = Attendance::where('employee_id', $employee->id)
                    ->where('date', $date)
                    ->first();

                // Jika tidak ada absensi atau tidak ada record, status default adalah ''
                $attendanceByDay[$date] = $attendanceRecord ? $attendanceRecord->status : '';
            }

            // Menyimpan data absensi pegawai dalam bentuk array
            $this->weeklyAttendance[] = [
                'employee_id' => $employee->id,
                'employee' => $employee->name,
                'attendance' => $attendanceByDay
            ];
        }
        $this->dispatchBrowserEvent('success', ['message' => 'Keterangan tersimpan']);
    }


    public function render()
    {
        return view('livewire.attendace.index', [
            'months' => $this->getMonths()
        ]);
    }

    private function getMonths()
    {
        // Mengambil daftar bulan mulai dari bulan sekarang hingga 12 bulan sebelumnya
        $start = Carbon::now();
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $months[] = $start->copy()->subMonths($i)->format('Y-m');
        }
        // Urutkan bulan dari yang terbaru ke terlama
        rsort($months);

        return $months;
    }
}
