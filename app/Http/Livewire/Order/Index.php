<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Goods;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;  // <-- Add this line to import Carbon




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

    public function validationDelete($id) // function menjalankan confirm delete
    {
        $this->dispatchBrowserEvent('validation', [
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $goods = Order::find($id)->goods;

        $deleted = Order::find($id)->delete();
        if ($deleted) {
            foreach ($goods as $good) {
                Log::info('Debug event triggered: ', ['id' => $good->id]);
                Goods::where('id', $good->id)->decrement('stock', $good->pivot->qty);
            }
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function render()
    {
        // Pastikan $startDate dan $endDate terisi dengan format yang benar
        $orders = Order::when($this->search, function ($query) {
            return $query->whereHas('supplier', function ($subquery) {
                $subquery->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('company', 'like', '%' . $this->search . '%');
            });
        })
            // Menambahkan pengecekan format tanggal, jika ada filter tanggal yang diinput
            ->when($this->startDate && $this->endDate, function ($query) {
                // Pastikan tanggal yang diberikan sudah dalam format yang benar
                return $query->whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.order.index', [
            'orders' => $orders,
        ]);
    }
}
