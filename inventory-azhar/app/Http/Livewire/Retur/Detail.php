<?php

namespace App\Http\Livewire\Retur;

use App\Models\retur;
use Livewire\Component;

class Detail extends Component
{
    public $returId;

    public function mount($id)
    {
        $this->returId = $id;
    }

    public function render()
    {
        $retur = retur::findOrFail($this->returId);

        return view('livewire.retur.detail', compact('retur'));
    }
}
