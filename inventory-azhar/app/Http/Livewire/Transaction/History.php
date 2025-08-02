<?php

namespace App\Http\Livewire\Transaction;

use App\Models\Customer;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
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

    public function validationDelete($id) // function menjalankan confirm delete
    {
        $this->dispatchBrowserEvent('validation', [
            'id' => $id
        ]);
    }


    public function delete($id)
    {
        $transaction = Transaction::findOrFail($id);

        // dd($transaction->balance);

        if ($transaction->balance > 0) {
            if ($transaction->balance < $transaction->total) {
                Customer::findOrFail($transaction->customer_id)->increment('balance', $transaction->balance);
            } elseif ($transaction->balance > $transaction->total) {
                Customer::findOrFail($transaction->customer_id)->increment('balance', $transaction->total);
            }
        }
        $deleted = $transaction->delete();

        if ($deleted) {
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function render()
    {
        $data = Transaction::where('status', '!=', 'hutang')
            ->when($this->search, function ($query) {
                return $query->whereHas('customer', function ($subquery) {
                    $subquery->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                return $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);  // Ensure you're using paginate here, not get()

        return view('livewire.transaction.history', [
            'data' => $data
        ]);
    }
}
