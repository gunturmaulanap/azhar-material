<?php

namespace App\Http\Livewire\Transaction;

use Livewire\Component;

class Old extends Component
{
    public $transaction;

    public function render()
    {
        return view('livewire.transaction.old');
    }
}
