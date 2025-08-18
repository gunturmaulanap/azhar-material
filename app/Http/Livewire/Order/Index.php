<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use App\Models\Goods;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search, $startDate, $endDate;
    public $perPage = 10;

    protected $listeners = [
        'confirm' => 'delete',   // dipanggil dari alert.blade.php (Livewire.emit('confirm', id))
        'perpage' => 'setPerPage',
    ];

    public function setPerPage($value)
    {
        $this->perPage = (int) $value ?: 10;
        $this->resetPage();
    }

    /** Munculkan dialog konfirmasi (sinkron dengan layouts/js/alert.blade.php) */
    public function validationDelete($id)
    {
        $this->dispatchBrowserEvent('toast:confirm', [
            'id'      => $id,
            'title'   => 'Hapus order?',
            'message' => 'Stok barang akan dikurangi sesuai qty order. Lanjutkan?',
        ]);
    }

    /** Eksekusi hapus ketika user klik YA di dialog konfirmasi */
    public function delete($id)
    {
        $order = Order::find($id);
        if (!$order) {
            $this->dispatchBrowserEvent('toast:error', ['message' => 'Order tidak ditemukan!']);
            return;
        }

        $goods = $order->goods; // many-to-many dengan pivot qty

        $deleted = $order->delete();
        if ($deleted) {
            foreach ($goods as $good) {
                Log::info('Revert stock by order delete', ['goods_id' => $good->id, 'qty' => $good->pivot->qty]);
                Goods::where('id', $good->id)->decrement('stock', $good->pivot->qty);
            }

            $this->dispatchBrowserEvent('toast:warning', [
                'message' => 'Order terhapus!'
            ]);

            $this->resetPage();
        } else {
            $this->dispatchBrowserEvent('toast:error', ['message' => 'Gagal menghapus order.']);
        }
    }

    public function render()
    {
        $orders = Order::when($this->search, function ($query) {
            return $query->whereHas('supplier', function ($subquery) {
                $subquery->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('company', 'like', '%' . $this->search . '%');
            });
        })
            ->when($this->startDate && $this->endDate, function ($query) {
                return $query->whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ]);
            })
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        return view('livewire.order.index', compact('orders'));
    }
}
