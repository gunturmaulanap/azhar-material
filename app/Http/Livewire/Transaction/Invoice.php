<?php

namespace App\Http\Livewire\Transaction;

use Livewire\Component;
use App\Models\Transaction;

class Invoice extends Component
{
    public $transactionId;
    public function mount($id)
    {
        $this->transactionId = $id;
    }

    public function render()
    {
        $transaction = Transaction::findOrFail($this->transactionId);

        return view('livewire.transaction.invoice', compact('transaction'))->layout('layouts.invoice');
    }
}
