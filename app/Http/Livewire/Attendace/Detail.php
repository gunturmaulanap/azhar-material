<?php

namespace App\Http\Livewire\Attendace;

use App\Models\Attendance;
use Livewire\Component;

class Detail extends Component
{
    public $attendanceId;

    public function mount($id)
    {
        $this->attendanceId = $id;
    }

    public function render()
    {
        return view('livewire.attendace.detail', [
            'attendance' => Attendance::findOrFail($this->attendanceId),
        ]);
    }
}
