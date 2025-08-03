<?php

namespace App\Http\Livewire\Master;

use App\Models\Customer;
use App\Models\Topup;
use App\Models\Transaction;
use Livewire\Component;

class CustomerDetail extends Component
{
    public $customerId, $balance = 0, $data;

    public function mount($id)
    {
        
        $this->customerId = $id;
        

        $this->data = Customer::findOrFail($this->customerId);
    }


    protected $rules = [
        'balance' => 'required',
    ];

    public function messages() //function untuk pesan error
    {
        return [
            'balance.required' => 'Nominal harus diisi.',
        ];
    }

    public function resetInput()
    {
        $this->reset('balance');
    }

    public function updated($fields) //function dari livewire untuk real-time validation
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        $this->validate();

        $topup = Topup::create([
            'customer_id' => $this->customerId,
            'before' => $this->data->balance,
            'nominal' => $this->balance,
            'after' => $this->data->balance + $this->balance,
        ]);

        if ($topup) {
            Customer::findOrFail($this->customerId)->increment('balance', $this->balance);

            return redirect()->route('master.detail-customer', ['id' => $this->customerId])->with('success', 'Transaksi berhasil!');
        }
    }

    public function render()
    {
        $customer = Customer::find($this->customerId);
        $transactions = Transaction::where('customer_id', $this->customerId)->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.master.customer-detail', [
            'customer' => $customer,
            'transactions' => $transactions,
        ]);
    }
}
