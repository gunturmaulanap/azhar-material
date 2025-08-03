<?php

namespace App\Http\Livewire\Report;

use App\Models\Transaction;
use Livewire\Component;
use Carbon\Carbon;

class Goods extends Component
{
    public $data;
    public $startDate, $endDate, $perMounth;
    public $search = ''; // Properti untuk wire:model

    public function mount()
    {
        // Set bulan saat ini ke perMounth sebagai default
        $this->perMounth = Carbon::now()->month;

        // Panggil filter data berdasarkan bulan saat ini
        $this->filterData();
    }
    public function updatedPerMounth()
    {
        $this->startDate = null;
        $this->endDate = null;
    }

    public function filterData()
    {
        $transactions = Transaction::query()
            ->when($this->perMounth, function ($query) {
                $query->whereMonth('created_at', '=', $this->perMounth)
                    ->whereYear('created_at', Carbon::now()->year);
            })
            ->when($this->startDate, function ($query) {
                $query->whereDate('created_at', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('created_at', '<=', $this->endDate);
            })
            ->where('status', '!=', 'hutang')
            ->with('goods')
            ->get();

        // Proses data goods dari transaksi
        $this->data = $transactions->flatMap(function ($transaction) {
            return $transaction->goods->map(function ($good) use ($transaction) {
                return [
                    'id' => $good->id,
                    'name' => $good->name,
                    'cost' => $good->cost,
                    'qty' => $good->pivot->qty,
                    'total_price' => $good->pivot->subtotal,
                    'transaction_date' => $transaction->created_at,
                ];
            });
        })->groupBy('id')->map(function ($group) {
            return [
                'name' => $group->first()['name'],
                'cost' => $group->first()['cost'],
                'qty' => $group->sum('qty'),
                'total_cost' => $group->sum(fn($item) => $item['cost'] * $item['qty']),
                'total_price' => $group->sum('total_price'),
                'profit' => $group->sum('total_price') - $group->sum(fn($item) => $item['cost'] * $item['qty']),
                'transaction_date' => $group->first()['transaction_date'],
            ];
        })->values();

        $this->filterBySearch(); // Filter hasil berdasarkan pencarian
    }

    public function filterBySearch()
    {
        // Jika properti search diisi, filter data berdasarkan nama barang
        if ($this->search) {
            $this->data = $this->data->filter(function ($item) {
                return stripos($item['name'], $this->search) !== false;
            })->values();
        }
    }


    public function updated($propertyName)
    {
        // Filter data setiap kali properti diperbarui
        $this->filterData();
    }

    public function render()
    {
        // Panggil filter data berdasarkan pencarian
        $data = $this->filterData();

        return view('livewire.report.goods', [
            'data' => $data, // Data yang difilter
        ]);
    }
}
