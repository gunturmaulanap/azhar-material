<?php

namespace App\Http\Livewire\Debt;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination; // Class dari livewire untuk fitur pagination

    public $search, $startDate, $endDate;
    public $perPage = 10;

    public function setPerPage($value)
    {
        $this->perPage = $value;
    }

    protected $listeners = [ // listeners handler untuk menjalankan delete setelah confirm
        'confirm' => 'delete',
        'perpage' => 'setPerPage',
    ];

    public function delete($id)
    {
        $deleted = Transaction::find($id)->delete();

        if ($deleted) {
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function render()
    {
        

        $data = Transaction::where('status', 'hutang')
        ->when($this->search, function ($query) {
            return $query->whereHas('customer', function ($subquery) {
                $subquery->where('name', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->startDate && $this->endDate, function ($query) {
            return $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        })
        ->orderBy('created_at', 'desc')
        ->paginate($this->perPage);

        return view('livewire.debt.index', [
            'data' => $data,
        ]);
    }
}
