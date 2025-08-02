<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use Livewire\Component;
use App\Models\GoodsOrder;
use App\Models\ReturOrder;
use Illuminate\Support\Facades\DB;

class Detail extends Component
{
    public $orderId, $goodsOrder;
    public $returGoods = [], $cashback;

    public function setReturData()
    {
        $goodsOrder = Order::findOrFail($this->orderId)->goods;
        foreach ($goodsOrder as $good) {
            $this->returGoods[] = [
                'order_id' => $this->orderId,
                'goods_id' => $good->id,
                'cost' => $good->pivot->cost,
                'retur_qty' => 0,
                'subcashback' => 0,
            ];
        }
    }

    public function updatedReturGoods($value, $propertyName)
    {
        $index = explode('.', $propertyName)[0];

        if ($value > $this->goodsOrder[$index]['qty']) {
            $this->dispatchBrowserEvent('error', ['message' => 'Melebihi jumlah barang pada transaksi']);
            $this->returGoods[$index]['retur_qty'] = $this->goodsOrder[$index]['qty'];
        }

        $this->returGoods[$index]['subcashback'] = $this->returGoods[$index]['cost'] * $this->returGoods[$index]['retur_qty'];
        $this->cashback = array_sum(array_column($this->returGoods, 'subcashback'));
    }

    public function increment($index)
    {
        $this->returGoods[$index]['retur_qty'] += 1;
        if ($this->returGoods[$index]['retur_qty'] <= $this->goodsOrder[$index]['qty']) {
            $this->returGoods[$index]['subcashback'] = $this->returGoods[$index]['cost'] * $this->returGoods[$index]['retur_qty'];
            $this->cashback = array_sum(array_column($this->returGoods, 'subcashback'));
        } else {
            $this->dispatchBrowserEvent('error', ['message' => 'Melebihi jumlah barang pada transaksi']);
            $this->returGoods[$index]['retur_qty'] = $this->goodsOrder[$index]['qty'];
        }
    }

    public function decrement($index)
    {
        $this->returGoods[$index]['retur_qty'] -= 1;
        if ($this->returGoods[$index]['retur_qty'] >= 0) {
            $this->returGoods[$index]['subcashback'] = $this->returGoods[$index]['cost'] * $this->returGoods[$index]['retur_qty'];
            $this->cashback = array_sum(array_column($this->returGoods, 'subcashback'));
        } else {
            $this->dispatchBrowserEvent('error', ['message' => 'Kurang dari jumlah barang pada transaksi']);
            $this->returGoods[$index]['retur_qty'] = 0;
        }
    }

    public function cancel()
    {
        $this->returGoods = [];
        $this->cashback = 0;
    }

    public function retur()
    {
        $allZero = array_reduce($this->returGoods, fn($carry, $good) => $carry && $good['retur_qty'] === 0, true);
        if (!$allZero) {
            $order = Order::findOrFail($this->orderId);
            $cashback = 0;

            foreach ($this->returGoods as $item) {
                $goodsOrder = GoodsOrder::where('order_id', $this->orderId)
                    ->where('goods_id', $item['goods_id'])
                    ->firstOrFail();

                $subcashback = $goodsOrder->cost * $item['retur_qty'];
                $cashback += $subcashback;
                $goodsOrder->decrement('qty', $item['retur_qty']);
                $goodsOrder->decrement('subtotal', $subcashback);

                if ($item['retur_qty'] > 0) {
                    ReturOrder::updateOrInsert(
                        [
                            'goods_id' => $item['goods_id'],
                            'order_id' => $this->orderId
                        ],
                        [
                            'cost' => $item['cost'],
                            'retur_qty' => DB::raw("COALESCE(retur_qty, 0) + {$item['retur_qty']}"),
                            'subcashback' => DB::raw("COALESCE(subcashback, 0) + {$subcashback}")
                        ]
                    );
                }
            }

            $order->decrement('total', $cashback);
            $order->save();
            $this->cancel();
            $this->dispatchBrowserEvent('success', ['message' => 'Berhasil retur barang']);
        } else {
            $this->dispatchBrowserEvent('error', ['message' => 'Pilih barang yang ingin di retur']);
        }
    }

    public function mount($id)
    {
        $this->orderId = $id;
        $this->goodsOrder = GoodsOrder::where('order_id', $this->orderId)->get();
    }

    public function render()
    {
        $order = Order::find($this->orderId);
        $returOrder = ReturOrder::where('order_id', $this->orderId)->get();

        return view('livewire.order.detail', [
            'order' => $order,
            'returOrder' => $returOrder,
        ]);
    }
}
