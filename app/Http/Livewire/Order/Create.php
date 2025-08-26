<?php

namespace App\Http\Livewire\Order;

use App\Models\Goods;
use App\Models\Order;
use Livewire\Component;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage; // ⬅️ Tambahkan baris ini

class Create extends Component
{
    use WithFileUploads;

    public $order = [
        'supplier_id' => null,
        'company'     => '',
        'name'        => '',
        'phone'       => '',
        'address'     => '',
        'keterangan'  => '',
        'status'      => 'selesai',
        'total'       => 0,
        'image'       => null,
    ];
    public $supplier, $delivery = false;
    public $searchSupplier, $search, $byCategory, $byBrand;
    public $goodOrders = [];
    public $imagePreview;

    public function updatedOrderImage()
    {
        if (isset($this->order['image'])) {
            $this->imagePreview = $this->order['image']->temporaryUrl();
        }
    }


    public function deleteImage()
    {
        $this->order['image'] = null;
        $this->imagePreview = null;
    }

    public function updatedDelivery($value)
    {
        $this->order['status'] = $value ? 'pengiriman' : 'selesai';
    }

    protected $rules = [
        'order.company' => 'required',
        'order.name' => 'required',
        'order.phone' => 'required',
    ];

    public function messages() //function untuk pesan toast:error
    {
        return [
            'order.company.required' => 'Nama perusahaan harus diisi.',
            'order.name.required' => 'Nama supplier harus diisi.',
            'order.phone.required' => 'Nomor Supplier harus diisi.',
        ];
    }

    public function updated($fields) //function dari livewire untuk real-time validation
    {
        $this->validateOnly($fields);
    }

    public function setSupplier($suppleirId)
    {
        $supplier = Supplier::find($suppleirId);
        $this->order['supplier_id'] = $supplier->id;
        $this->order['company'] = $supplier->company;
        $this->order['name'] = $supplier->name;
        $this->order['phone'] = $supplier->phone;
        $this->order['address'] = $supplier->address;
        $this->order['keterangan'] = $supplier->keterangan;

        $this->validate();
    }

    public function updatedOrderCompany()
    {
        if (isset($this->order['supplier_id'])) {
            $this->order['supplier_id'] = null;
        }
    }

    public function updatedOrderName()
    {
        if (isset($this->order['supplier_id'])) {
            $this->order['supplier_id'] = null;
        }
    }

    public function updatedOrderPhone()
    {
        if (isset($this->order['supplier_id'])) {
            $this->order['supplier_id'] = null;
        }
    }

    public function updatedOrderAddress()
    {
        if (isset($this->order['supplier_id'])) {
            $this->order['supplier_id'] = null;
        }
    }

    public function addGood($good_id)
    {
        $good = Goods::find($good_id);

        if (!$good) {
            $this->dispatchBrowserEvent('toast:error', ['message' => 'Barang tidak ditemukan']);
            return;
        }

        // Cek apakah barang sudah ada di goodOrders
        $productKey = collect($this->goodOrders)->search(function ($item) use ($good) {
            return $item['goods_id'] == $good->id;
        });

        if ($productKey !== false) {
            // Jika barang sudah ada, tampilkan pesan toast:error
            $this->dispatchBrowserEvent('toast:error', ['message' => 'Barang sudah terinput']);
        } else {
            // Jika barang belum ada, tambahkan sebagai entri baru
            $this->goodOrders[] = [
                'goods_id' => $good->id,
                'name' => $good->name,
                'cost' => $good->cost,
                'qty' => 1,
                'unit' => $good->unit,
                'subtotal' => $good->cost * 1,
            ];

            $this->calculateTotal();
            $this->dispatchBrowserEvent('success', ['message' => 'Barang berhasil ditambahkan']);
        }
    }


    public function updatedGoodOrders($value, $propertyName)
    {
        [$index, $field] = array_pad(explode('.', $propertyName), 2, null);

        if (in_array($field, ['cost', 'qty'])) {
            $num = preg_replace('/\D/', '', (string)$value);
            if ($field === 'qty') {
                $this->goodOrders[$index]['qty'] = max(1, (int)$num);
            } else {
                $this->goodOrders[$index]['cost'] = (float)$num;
            }
            $this->goodOrders[$index]['subtotal'] =
                ((float)($this->goodOrders[$index]['cost'] ?? 0)) * ((int)($this->goodOrders[$index]['qty'] ?? 0));
            $this->calculateTotal();
        }
    }

    public function calculateSubtotal($index)
    {
        // Pastikan cost dan qty adalah angka
        $cost = is_numeric($this->goodOrders[$index]['cost']) ? (float) $this->goodOrders[$index]['cost'] : 0;
        $qty = is_numeric($this->goodOrders[$index]['qty']) ? (int) $this->goodOrders[$index]['qty'] : 0;

        // Hitung subtotal
        $this->goodOrders[$index]['subtotal'] = $cost * $qty;

        // Hitung total
        $this->calculateTotal();
    }


    public function calculateTotal()
    {
        $this->order['total'] = array_sum(array_column($this->goodOrders, 'subtotal'));
    }

    public function increment($index)
    {
        $this->goodOrders[$index]['qty'] += 1;
        $this->goodOrders[$index]['subtotal'] = $this->goodOrders[$index]['cost'] * $this->goodOrders[$index]['qty'];
        $this->calculateTotal();
    }

    public function decrement($index)
    {
        if ($this->goodOrders[$index]['qty'] <= 1) {
            $this->goodOrders[$index]['qty'] = 1;
            $this->dispatchBrowserEvent('toast:error', ['message' => 'Tidak bisa kurang dari 1']);
        } else {
            $this->goodOrders[$index]['qty'] -= 1;
            $this->goodOrders[$index]['subtotal'] = $this->goodOrders[$index]['cost'] * $this->goodOrders[$index]['qty'];
            $this->calculateTotal();
        }
    }

    public function deleteGood($index)
    {
        unset($this->goodOrders[$index]);
        $this->goodOrders = array_values($this->goodOrders); // <-- reset index biar berurutan lagi
        $this->calculateTotal();
    }

    // Metode ini telah diubah total untuk menggunakan Storage facade
    public function setImage()
    {
        if (isset($this->order['image'])) {
            // Gunakan metode `store` dari Livewire untuk menyimpan file
            // Ini akan otomatis membuat folder `images/products` di dalam `storage/app/public`
            $this->order['image'] = $this->order['image']->store('images/products', 'public');
        } else {
            $this->order['image'] = null;
        }
    }

    public function resetInput()
    {
        return redirect()->route(str_replace('_', '', auth()->user()->role) . '.order.create');
    }

    public function save()
    {
        // Validasi: Jika barang belum diisi
        if (empty($this->goodOrders)) {
            $this->dispatchBrowserEvent('toast:error', ['message' => 'Silakan isi data barang terlebih dahulu']);
            return;
        }

        // Validasi field supplier
        try {
            $this->validate([
                'order.company' => 'required|string',
                'order.name'    => 'required|string',
                'order.phone'   => ['required', 'regex:/^\d{1,15}$/'],
            ], [
                'order.company.required' => 'Nama perusahaan harus diisi.',
                'order.name.required'    => 'Nama supplier harus diisi.',
                'order.phone.required'   => 'Nomor telp supplier harus diisi.',
                'order.phone.regex'      => 'Nomor telp harus berupa angka dan maksimal 15 digit.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Kalau validasi gagal → munculin toast
            $this->dispatchBrowserEvent('toast:error', ['message' => 'Silakan lengkapi data supplier!']);
            throw $e; // tetap lempar error biar Livewire render @error di blade
        }

        // Normalisasi nomor telepon
        $this->order['phone'] = preg_replace('/\D/', '', $this->order['phone'] ?? '');

        // Cek atau buat supplier baru
        $supplier = Supplier::firstOrCreate(
            ['phone' => $this->order['phone']],
            [
                'company'    => $this->order['company'],
                'name'       => $this->order['name'],
                'address'    => $this->order['address'] ?? '',
                'keterangan' => $this->order['keterangan'] ?? '',
                'status'     => 'Non Member',
                'balance'    => 0,
            ]
        );

        // Set supplier_id
        $this->order['supplier_id'] = $supplier->id;

        // Panggil metode setImage yang sudah diperbaiki
        $this->setImage();

        // Membuat Order baru
        $order = Order::create([
            'user_id'     => Auth::user()->id,
            'supplier_id' => $this->order['supplier_id'],
            'company'     => $this->order['company'],
            'name'        => $this->order['name'],
            'phone'       => $this->order['phone'],
            'address'     => $this->order['address'] ?? '',
            'total'       => $this->order['total'],
            'status'      => $this->order['status'] ?? 'selesai',
            'image'       => $this->order['image'] ?? null,
        ]);

        foreach ($this->goodOrders as $good) {
            // Tambahkan data ke tabel pivot
            $order->goods()->attach($good['goods_id'], [
                'cost'     => $good['cost'],
                'qty'      => $good['qty'],
                'subtotal' => $good['subtotal'],
            ]);

            // Increment stok barang
            Goods::where('id', $good['goods_id'])->increment('stock', $good['qty']);
        }

        if ($order) {
            return redirect()
                ->route(str_replace('_', '', auth()->user()->role) . '.order.index')
                ->with('success', 'Pesanan berhasil!');
        }
    }



    public function render()
    {
        $categories = Category::all();
        $brands = Brand::all();

        $suppliers = Supplier::when($this->searchSupplier, function ($query) {
            $query->search($this->searchSupplier);
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $goods = Goods::when($this->search, function ($query) {
            $query->search($this->search);
        })
            ->when($this->byCategory, function ($query) {
                $query->where('category_id', $this->byCategory);
            })
            ->when($this->byBrand, function ($query) {
                $query->where('brand_id', $this->byBrand);
            })
            ->orderByRaw("CAST(name AS UNSIGNED), name ASC")
            ->paginate(20);

        $categories = Cache::remember('categories', 3600, function () {
            return Category::orderBy('name', 'asc')->get();
        });
        $brands = Cache::remember('brands', 3600, function () {
            return Brand::orderBy('name', 'asc')->get();
        });

        return view('livewire.order.create', [
            'suppliers' => $suppliers,
            'goods' => $goods,
            'brands' => Brand::orderBy('name', 'asc')->get(),
            'categories' => Category::orderBy('name', 'asc')->get(),
        ]);
    }
}
