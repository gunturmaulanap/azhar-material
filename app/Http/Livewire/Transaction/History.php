<?php

namespace App\Http\Livewire\Transaction;

use App\Models\Customer;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public $search, $startDate, $endDate;
    public $perPage = 10;

    protected $listeners = [
        // ketika dialog konfirmasi pilih YA, alert.js akan emit 'confirm'
        'confirm' => 'delete',
        'perpage' => 'setPerPage',
    ];

    public function setPerPage($value)
    {
        $this->perPage = $value;
    }

    /** Tampilkan dialog konfirmasi (sesuai alert.blade.php) */
    public function validationDelete($id)
    {
        $this->dispatchBrowserEvent('toast:confirm', [
            'id'      => $id,
            'title'   => 'Hapus transaksi?',
            'message' => 'Tindakan ini tidak bisa dibatalkan.',
        ]);
    }

    /** Eksekusi hapus saat user menekan YA di dialog */
    public function delete($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->balance > 0) {
            if ($transaction->balance < $transaction->total) {
                Customer::findOrFail($transaction->customer_id)->increment('balance', $transaction->balance);
            } elseif ($transaction->balance > $transaction->total) {
                Customer::findOrFail($transaction->customer_id)->increment('balance', $transaction->total);
            }
        }

        $deleted = $transaction->delete();

        if ($deleted) {
            // sesuaikan dengan listener di alert.blade.php
            $this->dispatchBrowserEvent('toast:warning', [
                'message' => 'Data terhapus!',
            ]);
        } else {
            $this->dispatchBrowserEvent('toast:error', [
                'message' => 'Gagal menghapus data.',
            ]);
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
            ->paginate($this->perPage);

        return view('livewire.transaction.history', [
            'data' => $data
        ]);
    }
}
