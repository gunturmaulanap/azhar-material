<?php

namespace App\Http\Livewire\Transaction;

use App\Models\ActDebt;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\GoodsTransaction;
use App\Models\Delivery;
use App\Models\DeliveryGoods;

use App\Models\ReturTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Detail extends Component
{
    public $transactionId, $goodsTransaction;
    public $input, $returGoods = [], $total;
    public $goodsAddition = [];
    public $maxBill;

    public function setReturData()
    {
        $goodsTransaction = Transaction::findOrFail($this->transactionId)->goods;
        foreach ($goodsTransaction as $good) {
            $this->returGoods[] = [
                'transaction_id' => $this->transactionId,
                'goods_id' => $good->id,
                'price' => $good->pivot->price,
                'retur_qty' => 0,
                'subcashback' => 0,
            ];
        }
    }

    public function updatedReturGoods($value, $propertyName)
    {
        $index = explode('.', $propertyName)[0];

        if ($value > $this->goodsTransaction[$index]['qty']) {
            $this->dispatchBrowserEvent('error', ['message' => 'Melebihi jumlah barang pada transaksi']);
            $this->returGoods[$index]['retur_qty'] = $this->goodsTransaction[$index]['qty'];
        }

        $this->returGoods[$index]['subcashback'] = $this->returGoods[$index]['price'] * $this->returGoods[$index]['retur_qty'];
        $this->total = array_sum(array_column($this->returGoods, 'subcashback'));
    }

    public function increment($index)
    {
        $this->returGoods[$index]['retur_qty'] += 1;
        if ($this->returGoods[$index]['retur_qty'] <= $this->goodsTransaction[$index]['qty']) {
            $this->returGoods[$index]['subcashback'] = $this->returGoods[$index]['price'] * $this->returGoods[$index]['retur_qty'];
            $this->total = array_sum(array_column($this->returGoods, 'subcashback'));
        } else {
            $this->dispatchBrowserEvent('error', ['message' => 'Melebihi jumlah barang pada transaksi']);
            $this->returGoods[$index]['retur_qty'] = $this->goodsTransaction[$index]['qty'];
        }
    }

    public function decrement($index)
    {
        $this->returGoods[$index]['retur_qty'] -= 1;
        if ($this->returGoods[$index]['retur_qty'] >= 0) {
            $this->returGoods[$index]['subcashback'] = $this->returGoods[$index]['price'] * $this->returGoods[$index]['retur_qty'];
            $this->total = array_sum(array_column($this->returGoods, 'subcashback'));
        } else {
            $this->dispatchBrowserEvent('error', ['message' => 'Kurang dari jumlah barang pada transaksi']);
            $this->returGoods[$index]['retur_qty'] = 0;
        }
    }

    public function cancel()
    {
        $this->returGoods = [];
        $this->total = 0;
    }

    public function retur()
    {
        $allZero = array_reduce($this->returGoods, fn($carry, $good) => $carry && $good['retur_qty'] === 0, true);

        if (!$allZero) {
            $transaction = Transaction::findOrFail($this->transactionId);
            $customer = Customer::findOrFail($transaction->customer_id);
            // $Delivery = Delivery::where('transaction_id', $transaction->id)->first();


            $cashback = 0;

            foreach ($this->returGoods as $item) {
                $goodsTransaction = GoodsTransaction::where('transaction_id', $this->transactionId)
                    ->where('goods_id', $item['goods_id'])
                    ->firstOrFail();

                $subcashback = $goodsTransaction->price * $item['retur_qty'];
                $cashback += $subcashback;
                $goodsTransaction->decrement('qty', $item['retur_qty']);
                $goodsTransaction->decrement('subtotal', $subcashback);

                if ($item['retur_qty'] > 0) {
                    ReturTransaction::updateOrInsert(
                        [
                            'goods_id' => $item['goods_id'],
                            'transaction_id' => $this->transactionId,
                        ],
                        [
                            'price' => $item['price'],
                            'retur_qty' => DB::raw("COALESCE(retur_qty, 0) + {$item['retur_qty']}"),
                            'subcashback' => DB::raw("COALESCE(subcashback, 0) + {$subcashback}"),
                        ]
                    );

                    // Tambahkan logika untuk barang yang telah dikirimkan (deliveryGoods)
                    $delivery = Delivery::where('transaction_id', $this->transactionId)->first(); // Ambil data pengiriman berdasarkan transaksi
                    if ($delivery) {
                        $goodDelivery = DeliveryGoods::where('delivery_id', $delivery->id)
                            ->where('goods_id', $item['goods_id'])
                            ->first();

                        if ($goodDelivery) {
                            $goodDelivery->decrement('qty', $item['retur_qty']); // Kurangi jumlah barang di pengiriman
                        }
                    }
                }
            }

            // Logika pengelolaan saldo dan hutang tetap
            if ($transaction->balance > 0) { // Jika ada saldo
                $transaction->decrement('total', $cashback);
                $transaction->grand_total = max(0, $transaction->total - $transaction->balance);

                if ($transaction->return >= 0) { // Tidak ada hutang
                    if ($transaction->bill > 0) { // Ada tambahan uang tunai
                        $transaction->return = $transaction->bill - $transaction->grand_total;
                        $difference = max(0, $transaction->balance - $transaction->total);
                        if ($difference > 0) {
                            $customer->increment('balance', $difference);
                        }
                    } else { // Tidak ada tambahan uang tunai
                        $customer->increment('balance', $cashback);
                    }
                } else { // Ada hutang
                    if ($transaction->grand_total <= 0) {
                        $transaction->grand_total = 0;
                        $difference = $transaction->balance - $transaction->total;
                        $customer->increment('balance', $difference);
                        $customer->decrement('debt', abs($transaction->return));
                        $customer->debt = max(0, $customer->debt);
                        $customer->save();
                        $transaction->return = 0;
                    } else {
                        $transaction->increment('return', $cashback);
                        $customer->decrement('debt', $cashback);
                        $customer->debt = max(0, $customer->debt);
                        $customer->save();
                    }
                }
            } else { // Tidak ada saldo
                $transaction->decrement('total', $cashback);
                $transaction->decrement('grand_total', $cashback);
                $transaction->return = $transaction->bill - $transaction->total;

                if ($transaction->return < 0) { // Ada hutang
                    $customer->decrement('debt', $cashback);
                }
            }
            // Tambahkan logika status
            if ($transaction->return > 0) {
                $delivery = Delivery::where('transaction_id', $transaction->id)->first();

                if ($delivery) {
                    $allDelivered = DeliveryGoods::where('delivery_id', $delivery->id)
                        ->get()
                        ->every(fn($deliveryGood) => $deliveryGood->delivered >= $deliveryGood->qty);

                    if ($allDelivered) {
                        $delivery->update(['status' => 'selesai']);
                        $transaction->update(['status' => 'selesai']);
                    } else {
                        $transaction->update(['status' => 'pengiriman']);
                    }
                } else {
                    $transaction->update(['status' => 'selesai']);
                }
            } else if ($transaction->return < 0) {
                $transaction->update(['status' => 'hutang']);
            }
            $transaction->save();
            $this->returGoods = [];
            $this->total = 0;
            $this->dispatchBrowserEvent('success', ['message' => 'Berhasil retur barang']);
        } else {
            $this->dispatchBrowserEvent('error', ['message' => 'Pilih barang yang ingin di retur']);
        }
    }

    public function pay()
    {
        if (!empty($this->input['bill'])) {
            $transaction = Transaction::findOrFail($this->transactionId);

            // Increment pembayaran dan update return
            $transaction->increment('bill', $this->input['bill']);
            $transaction->increment('return', $this->input['bill']);
            Customer::findOrFail($transaction->customer_id)->decrement('debt', $this->input['bill']);

            // Cek apakah pembayaran cukup untuk melunasi transaksi
            if ($transaction->bill < $transaction->grand_total) {
                // Jika pembayaran belum mencukupi Grand Total, tetap status hutang
                $transaction->update(['status' => 'hutang']);
            } else {
                // Pembayaran cukup untuk melunasi transaksi
                $delivery = Delivery::where('transaction_id', $transaction->id)->first();

                if ($transaction->return <= 0) {
                    // Jika hutang lunas, cek status pengiriman
                    if ($delivery) {
                        $allDelivered = DeliveryGoods::where('delivery_id', $delivery->id)
                            ->get()
                            ->every(fn($deliveryGood) => $deliveryGood->delivered >= $deliveryGood->qty);

                        if ($allDelivered) {
                            // Semua barang sudah terkirim
                            $delivery->update(['status' => 'selesai']);
                            $transaction->update(['status' => 'selesai']);
                        } else {
                            // Masih ada barang yang belum terkirim
                            $delivery->update(['status' => 'pengiriman']);
                            $transaction->update(['status' => 'pengiriman']);
                        }
                    } else {
                        // Tidak ada data pengiriman, set status selesai jika hutang lunas
                        $transaction->update(['status' => 'selesai']);
                    }
                } else if ($transaction->return > 0) {
                    // Jika ada kelebihan pembayaran, cek pengiriman
                    if ($delivery) {
                        $allDelivered = DeliveryGoods::where('delivery_id', $delivery->id)
                            ->get()
                            ->every(fn($deliveryGood) => $deliveryGood->delivered >= $deliveryGood->qty);

                        if ($allDelivered) {
                            $delivery->update(['status' => 'selesai']);
                            $transaction->update(['status' => 'selesai']);
                        } else {
                            $delivery->update(['status' => 'pengiriman']);
                            $transaction->update(['status' => 'pengiriman']);
                        }
                    } else {
                        // Jika tidak ada data pengiriman, tetap set status pengiriman
                        $transaction->update(['status' => 'pengiriman']);
                    }
                }
            }

            // Catat pembayaran ke tabel ActDebt
            ActDebt::create([
                'transaction_id' => $this->transactionId,
                'user_id' => Auth::user()->id,
                'pay' => $this->input['bill'],
            ]);

            // Reset input bill dan beri notifikasi keberhasilan
            $this->input['bill'] = 0;
            $this->dispatchBrowserEvent('success', ['message' => 'Berhasil membayar']);
        } else {
            // Jika nominal tidak diisi, beri notifikasi error
            $this->dispatchBrowserEvent('error', ['message' => 'Input nominal']);
        }
    }




    public function mount($id)
    {
        $this->transactionId = $id;
        $this->goodsTransaction = GoodsTransaction::where('transaction_id', $this->transactionId)->get();
    }

    public function render()
    {
        $transaction = Transaction::findOrFail($this->transactionId);
        $delivery = Delivery::where('transaction_id', $transaction->id)->first();

        // Cek apakah transaksi memiliki hutang
        if ($transaction->return < 0) {
            // Jika ada hutang, status transaksi adalah "hutang"
            $transaction->update(['status' => 'hutang']);
        } elseif ($transaction->return >= 0) {
            // Jika tidak ada hutang
            if ($delivery) {
                // Jika ada pengiriman
                $allDelivered = DeliveryGoods::where('delivery_id', $delivery->id)
                    ->get()
                    ->every(fn($deliveryGood) => $deliveryGood->delivered >= $deliveryGood->qty);

                if ($allDelivered) {
                    // Jika semua barang sudah terkirim, statusnya "selesai"
                    $transaction->update(['status' => 'selesai']);
                    $delivery->update(['status' => 'selesai']);
                } else {
                    // Jika masih ada barang yang belum terkirim, statusnya "pengiriman"
                    $transaction->update(['status' => 'pengiriman']);
                    $delivery->update(['status' => 'pengiriman']);
                }
            } else {
                // Jika tidak ada pengiriman, statusnya "selesai"
                $transaction->update(['status' => 'selesai']);
            }
        }

        // Ambil data retur transaksi untuk ditampilkan di view
        $returTransactions = ReturTransaction::where('transaction_id', $this->transactionId)->get();

        return view('livewire.transaction.detail', [
            'transaction' => $transaction,
            'returTransactions' => $returTransactions,
        ]);
    }
}
