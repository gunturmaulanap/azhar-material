<?php

namespace App\Http\Livewire\Report;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Order;
use Livewire\WithPagination;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $search, $startDate, $endDate, $perMounth, $currentYear;

    public function mount()
    {
        $this->currentYear = Carbon::now()->year; // Set tahun saat ini
    }

    public function updatedPerMounth()
    {
        $this->startDate = null;
        $this->endDate = null;
    }

    public function render()
    {
        $data = $this->filterData();
        $debt = $this->filterDebt();
        $orders = $this->filterOrders();

        return view('livewire.report.index', [
            'data' => $data,
            'debt' => $debt,
            'orders' => $orders,
        ]);
    }

    public function filterData()
    {
        return Transaction::when($this->perMounth, function ($query) {
            $query->whereMonth('created_at', '=', $this->perMounth)
                ->whereYear('created_at', '=', $this->currentYear); // Filter berdasarkan tahun saat ini
        })
            ->when(!$this->perMounth, function ($query) {
                $query->when($this->startDate, function ($query) {
                    $query->whereDate('created_at', '>=', $this->startDate);
                })
                    ->when($this->endDate, function ($query) {
                        $query->whereDate('created_at', '<=', $this->endDate);
                    });
            })->where('status', '!=', 'hutang')
            ->get();
    }

    public function filterDebt()
    {
        return Transaction::when($this->perMounth, function ($query) {
            $query->whereMonth('created_at', '=', $this->perMounth)
                ->whereYear('created_at', '=', $this->currentYear); // Filter berdasarkan tahun saat ini
        })
            ->when(!$this->perMounth, function ($query) {
                $query->when($this->startDate, function ($query) {
                    $query->whereDate('created_at', '>=', $this->startDate);
                })
                    ->when($this->endDate, function ($query) {
                        $query->whereDate('created_at', '<=', $this->endDate);
                    });
            })->where('status', 'hutang')
            ->get();
    }

    public function filterOrders()
    {
        return Order::when($this->perMounth, function ($query) {
            $query->whereMonth('created_at', '=', $this->perMounth)
                ->whereYear('created_at', '=', $this->currentYear); // Filter berdasarkan tahun saat ini
        })
            ->when(!$this->perMounth, function ($query) {
                $query->when($this->startDate, function ($query) {
                    $query->whereDate('created_at', '>=', $this->startDate);
                })
                    ->when($this->endDate, function ($query) {
                        $query->whereDate('created_at', '<=', $this->endDate);
                    });
            })
            ->get();
    }
}
