<?php

namespace App\Http\Livewire\Transaction;

use App\Models\Brand;
use App\Models\Goods;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Transaction;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan ini

class Create extends Component
{
    use WithFileUploads;

    public $transaction, $customer = false;
    public $searchCustomer, $search, $byCategory, $byBrand;

    public $goodTransaction = [];
    public $imagePreview;
    public $query;

    public function updatedTransactionImage()
    {
        if (isset($this->transaction['image'])) {
            $this->imagePreview = $this->transaction['image']->temporaryUrl();
        }
    }

    public function deleteImage()
    {
        $this->transaction['image'] = null;
        $this->imagePreview = null;
    }

    protected $rules = [
        'transaction.name' => 'required',
        'transaction.phone' => 'required',
    ];

    public function messages() //function untuk pesan error
    {
        return [
            'transaction.name.required' => 'Nama customer harus diisi.',
            'transaction.phone.required' => 'Nomor customer harus diisi.',
        ];
    }

    public function updated($fields) //function dari livewire untuk real-time validation
    {
        $this->validateOnly($fields);
    }

    public function updatedTransaction($value, $propertyName)
    {
        if (in_array($propertyName, ['name', 'phone', 'address'])) {
            $this->transaction['balance'] = null;
        }
    }

    public function resetInput()
    {
        return redirect()->route(str_replace('_', '', auth()->user()->role) . '.transaction.create');
    }


    public function updatedCustomer($value)
    {
        if ($this->customer === false) {
            $this->transaction['customer_id'] = 1; // Pelanggan anonim
            $this->transaction['name'] = null;
            $this->transaction['phone'] = null; // Reset nomor telepon
            $this->transaction['address'] = null;
            $this->transaction['balance'] = null;
        }
    }



    public function setCustomer($customerId)
    {
        $cust = Customer::find($customerId);
        $this->transaction['customer_id'] = $cust->id;
        $this->transaction['name'] = $cust->name;
        $this->transaction['phone'] = $cust->phone;
        $this->transaction['address'] = $cust->address;
        $this->transaction['balance'] = $cust->balance;


        if (isset($this->transaction['discount'])) {
            $this->transaction['discount'] = 0;
        }
        $this->calculateTotal();
    }

    public function addGood($good_id)
    {
        $good = Goods::find($good_id);
        $productKey = collect($this->goodTransaction)->search(function ($item) use ($good) {
            return $item['goods_id'] == $good->id;
        });

        if ($productKey !== false) {
            $this->dispatchBrowserEvent('error', ['message' => 'Barang sudah terinput']);
        } else {
            // Jika barang belum ada, tambahkan sebagai entri baru
            $this->goodTransaction[] = [
                'goods_id' => $good->id,
                'name' => $good->name,
                'price' => $good->price,
                'qty' => 1,
                'unit' => $good->unit,
                'subtotal' => $good->price,
            ];
        }

        $this->calculateTotal();
    }


    public function updatedGoodTransaction($value, $propertyName)
    {
        $index = explode('.', $propertyName)[0];

        if ($value > Goods::find($this->goodTransaction[$index]['goods_id'])->stock) {
            $this->dispatchBrowserEvent('error', ['message' => 'Barang melebihi stok yang ada']);
            $this->goodTransaction[$index]['qty'] = Goods::find($this->goodTransaction[$index]['goods_id'])->stock;
        }

        $this->goodTransaction[$index]['subtotal'] = $this->goodTransaction[$index]['price'] * $this->goodTransaction[$index]['qty'];
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $subtotal = array_sum(array_column($this->goodTransaction, 'subtotal'));
        $this->transaction['total'] = $subtotal;
        $this->transaction['grand_total'] = $subtotal;

        if (isset($this->transaction['balance'])) {
            $this->transaction['grand_total'] = $this->transaction['total'] - $this->transaction['balance'];
            if ($this->transaction['grand_total'] < 0) {
                $this->transaction['grand_total'] = 0;
            }
        }

        if (isset($this->transaction['discount'])) {
            $this->updatedTransactionDiscount($this->transaction['discount']);
        }

        if (isset($this->transaction['bill'])) {
            $this->transaction['return'] = $this->transaction['bill'] - $this->transaction['grand_total'];
        } else {
            $this->transaction['return'] = 0 - $this->transaction['grand_total'];
        }
    }

    public function updatedTransactionDiscount($value)
    {
        $max = (int) ($this->transaction['total'] ?? 0);
        $v = (int) preg_replace('/\D/', '', (string) $value);
        $v = max(0, min($v, $max));
        $this->transaction['discount'] = $v;

        $balance = (int) ($this->transaction['balance'] ?? 0);
        $total   = (int) ($this->transaction['total'] ?? 0);

        // grand total = total - discount - balance (minimal 0)
        $grand = max(0, $total - $v - $balance);
        $this->transaction['grand_total'] = $grand;

        // jika bill kebablasan melebihi sisa (grand), rapikan ke grand
        $bill = (int) ($this->transaction['bill'] ?? 0);
        if ($bill > $grand) {
            $bill = $grand;
            $this->transaction['bill'] = $bill;
        }

        // return = bill - grand
        $this->transaction['return'] = $bill - $grand;
    }

    public function updatedTransactionBill($value)
    {
        // BATAS bayar adalah 'kurang' = grand_total saat ini
        $max = (int) ($this->transaction['grand_total'] ?? 0);
        $v = (int) preg_replace('/\D/', '', (string) $value);
        $v = max(0, min($v, $max));
        $this->transaction['bill'] = $v;

        $grand = (int) ($this->transaction['grand_total'] ?? 0);
        $this->transaction['return'] = $v - $grand;
    }

    public function increment($index)
    {
        $this->goodTransaction[$index]['qty'] += 1;
        if ($this->goodTransaction[$index]['qty'] > Goods::find($this->goodTransaction[$index]['goods_id'])->stock) {
            $this->goodTransaction[$index]['qty'] = Goods::find($this->goodTransaction[$index]['goods_id'])->stock;
            $this->dispatchBrowserEvent('error', ['message' => 'Barang melebihi stok yang ada']);
        }
        $this->goodTransaction[$index]['subtotal'] = $this->goodTransaction[$index]['price'] * $this->goodTransaction[$index]['qty'];
        $this->calculateTotal();
    }

    public function decrement($index)
    {
        if ($this->goodTransaction[$index]['qty'] <= 1) {
            $this->goodTransaction[$index]['qty'] = 1;
            $this->dispatchBrowserEvent('error', ['message' => 'Tidak bisa kurang dari 1']);
        } else {
            $this->goodTransaction[$index]['qty'] -= 1;
            $this->goodTransaction[$index]['subtotal'] = $this->goodTransaction[$index]['price'] * $this->goodTransaction[$index]['qty'];
            $this->calculateTotal();
        }
    }

    public function deleteGood($index)
    {
        unset($this->goodTransaction[$index]);
        array_values($this->goodTransaction);
        $this->calculateTotal();
    }

    public function preparation()
    {
        // Validasi: Jika customer_id == 1 dan return < 0, hentikan proses di awal
        if (isset($this->transaction['customer_id']) && $this->transaction['customer_id'] == 1 && ($this->transaction['return'] ?? 0) < 0) {
            $this->dispatchBrowserEvent('error', [
                'message' => 'Customer tanpa nama dan nomor telepon tidak dapat berhutang'
            ]);
            return false; // Hentikan proses
        }

        $hasDelivery = !empty(array_filter($this->goodTransaction, function ($item) {
            return isset($item['delivery']) && $item['delivery'] === true;
        }));

        // Penentuan status transaksi
        $this->transaction['status'] = isset($this->transaction['return'])
            ? ($this->transaction['return'] < 0
                ? 'hutang'
                : ($hasDelivery
                    ? 'pengiriman'
                    : 'selesai'))
            : 'selesai';

        // Validasi tambahan untuk status 'hutang' pada customer_id == 1
        if (isset($this->transaction['customer_id']) && $this->transaction['customer_id'] == 1 && $this->transaction['status'] === 'hutang') {
            $this->dispatchBrowserEvent('error', [
                'message' => 'Transaksi tidak dapat dilakukan karena status hutang'
            ]);
            return false; // Hentikan proses
        }

        // --- PERBAIKAN UTAMA ADA DI BAGIAN INI ---
        if (isset($this->transaction['image']) && !empty($this->transaction['image'])) {
            // Gunakan Storage facade Livewire untuk menyimpan file
            // Ini akan otomatis membuat folder jika belum ada
            $this->transaction['image'] = $this->transaction['image']->store('images/products', 'public');
        } else {
            $this->transaction['image'] = null;
        }

        return true; // Kembali true jika semua validasi lolos
    }

    public function decrementBalance()
    {
        if (isset($this->transaction['balance'])) {
            $grandTotal = $this->transaction['total'] - ($this->transaction['discount'] ?? 0);
            $balance = $this->transaction['balance'];

            Customer::findOrFail($this->transaction['customer_id'] ?? 1)
                ->decrement('balance', min($balance, $grandTotal));
        }
    }

    public function incrementDebt()
    {
        if (isset($this->transaction['return']) && $this->transaction['return'] < 0) {
            Customer::findOrFail($this->transaction['customer_id'] ?? 1)
                ->increment('debt', abs($this->transaction['return']));
        }
    }


    public function save()
    {
        // Validasi awal: Pastikan barang telah dipilih
        if (empty($this->goodTransaction)) {
            $this->dispatchBrowserEvent('error', ['message' => 'Pilih barang terlebih dahulu']);
            return false;
        }

        // Validasi customer
        if ($this->customer) {
            if (isset($this->transaction['name']) && isset($this->transaction['phone'])) {
                $this->validate(
                    ['transaction.phone' => 'max:15'],
                    ['transaction.phone.max' => 'Nomor telp customer terlalu panjang. Maksimal 15 karakter']
                );

                // Cek atau buat customer baru
                $customer = Customer::firstOrCreate(
                    ['phone' => $this->transaction['phone'], 'name' => $this->transaction['name']],
                    ['address' => $this->transaction['address'] ?? '', 'status' => 'Non Member', 'balance' => 0]
                );

                // Mengatur customer_id
                $this->transaction['customer_id'] = $customer->id;
            } else {
                $this->validate(
                    [
                        'transaction.name' => 'required',
                        'transaction.phone' => 'required',
                    ],
                    [
                        'transaction.name.required' => 'Nama customer tidak boleh kosong.',
                        'transaction.phone.required' => 'Nomor telp customer tidak boleh kosong.',
                    ]
                );
            }
        }

        // Panggil fungsi preparation
        if (!$this->preparation()) {
            return false; // Jika preparation gagal, hentikan proses
        }

        // Buat transaksi
        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'customer_id' => $this->transaction['customer_id'] ?? 1,
            'name' => $this->transaction['name'] ?? 'Guest',
            'address' => $this->transaction['address'] ?? '',
            'phone' => $this->transaction['phone'] ?? '',
            'total' => $this->transaction['total'],
            'discount' => $this->transaction['discount'] ?? 0,
            'grand_total' => $this->transaction['grand_total'],
            'balance' => $this->transaction['balance'] ?? 0,
            'bill' => $this->transaction['bill'] ?? 0,
            'return' => $this->transaction['return'] ?? $this->transaction['grand_total'],
            'status' => $this->transaction['status'],
            'image' => $this->transaction['image'] ?? null,
        ]);

        // Tambahkan barang ke transaksi
        foreach ($this->goodTransaction as $good) {
            $transaction->goods()->attach($good['goods_id'], [
                'price' => $good['price'],
                'qty' => $good['qty'],
                'subtotal' => $good['subtotal'],
                'delivery' => $good['delivery'] ?? false,
            ]);
        }

        // Proses pengiriman barang (jika ada)
        $deliveryGoods = array_filter($this->goodTransaction, function ($item) {
            return isset($item['delivery']) && $item['delivery'] === true;
        });

        if (!empty($deliveryGoods)) {
            $delivery = Delivery::create([
                'transaction_id' => $transaction->id,
                'status' => 'pengiriman',
            ]);

            foreach ($deliveryGoods as $item) {
                $delivery->goods()->attach($item['goods_id'], [
                    'qty' => $item['qty'],
                ]);
            }
        }

        // Logika untuk customer bukan anonim
        if (isset($this->transaction['customer_id']) && $this->transaction['customer_id'] !== 1) {
            $this->decrementBalance();
            $this->incrementDebt();
        }

        // Redirect setelah semua selesai
        return redirect()->route(str_replace('_', '', auth()->user()->role) . '.transaction.detail', ['id' => $transaction->id])
            ->with('success', 'Transaksi berhasil!');
    }

    public function render()
    {
        $this->transaction['customer_id'] = $this->transaction['customer_id'] ?? 1;
        $customers = Customer::where('id', '!=', 1)
            ->when($this->searchCustomer, function ($query) {
                $query->search($this->searchCustomer); // menjalankan query search
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $goods = Goods::when($this->search, function ($query) {
            $query->search($this->search); // menjalankan query search
        })->when($this->byCategory, function ($query) {
            $query->where('category_id', $this->byCategory); // menjalankan query by Category
        })
            ->when($this->byBrand, function ($query) {
                $query->where('brand_id', $this->byBrand); // menjalankan query by Category
            })
            ->orderByRaw("CAST(name AS UNSIGNED), name ASC")
            ->paginate(20);

        $categories = Cache::remember('categories', 3600, function () {
            return Category::orderBy('name', 'asc')->get();
        });
        $brands = Cache::remember('brands', 3600, function () {
            return Brand::orderBy('name', 'asc')->get();
        });

        return view('livewire.transaction.create', [
            'customers' => $customers,
            'goods' => $goods,
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }
}
