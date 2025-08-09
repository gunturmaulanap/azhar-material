<?php

namespace App\Http\Livewire\Delivery; // Sesuaikan dengan namespace komponen Anda

use Livewire\Component;
use App\Models\Transaction; // Asumsikan model Transaction ada di App\Models
use App\Models\Delivery;
use App\Models\DeliveryGoods;
use Livewire\WithPagination; // Penting jika Anda menggunakan pagination

class Index extends Component // Ganti 'Index' dengan nama kelas komponen Anda jika berbeda
{
    use WithPagination;

    public $search = '';
    public $startDate; // Asumsikan properti ini ada
    public $endDate;   // Asumsikan properti ini ada
    public $perPage = 10; // Asumsikan properti ini ada untuk pagination
    public $transaction; // Properti baru untuk menyimpan ID transaksi yang akan difilter
    public $transactionId; // Properti baru untuk menyimpan ID transaksi yang akan difilter

    // Anda mungkin perlu menambahkan metode updatedTransactionId() jika Anda ingin
    // query diperbarui secara real-time saat $transactionId berubah di frontend.
    // public function updatedTransactionId()
    // {
    //     $this->resetPage(); // Reset pagination saat filter berubah
    // }

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


    // public function delete($id)
    // {
    //     $transaction = Transaction::findOrFail($id);

    //     // dd($transaction->balance);

    //     if ($transaction->balance > 0) {
    //         if ($transaction->balance < $transaction->total) {
    //             Customer::findOrFail($transaction->customer_id)->increment('balance', $transaction->balance);
    //         } elseif ($transaction->balance > $transaction->total) {
    //             Customer::findOrFail($transaction->customer_id)->increment('balance', $transaction->total);
    //         }
    //     }
    //     $deleted = $transaction->delete();

    //     if ($deleted) {
    //         $this->dispatchBrowserEvent('deleted');
    //     }
    // }

    public function render()
    {
        // Update status for all transactions with 'pengiriman' status before filtering
        $transactions = Transaction::where('status', 'pengiriman')->get();
        
        foreach ($transactions as $transaction) {
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
        }

        // After updating statuses, query only transactions that still have 'pengiriman' status
        $data = Transaction::query() // Mulai dengan query dasar
            ->where('status', 'pengiriman') // Menambahkan filter status 'pengiriman'
            ->when($this->transactionId, function ($query) {
                // Pastikan $this->transactionId tidak kosong/null sebelum digunakan
                return $query->where('id', $this->transactionId); // Filter berdasarkan ID transaksi
            })
            ->when($this->search, function ($query) {
                return $query->whereHas('customer', function ($subquery) {
                    $subquery->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                return $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);  // Ensure you're using paginate here, not get()

        return view('livewire.delivery.index', [
            'data' => $data
        ]);
    }
}
