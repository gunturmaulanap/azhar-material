<?php

namespace App\Http\Livewire\Retur;

use App\Models\Goods;
use App\Models\retur;
use Livewire\Component;
use App\Models\Category;
use App\Models\Supplier;

class Create extends Component
{
    public $retur, $supplier;
    public $searchSupplier, $search, $byCategory;
    public $goodReturs = [];

    protected $rules = [
        'retur.company' => 'required',
        'retur.name' => 'required',
        'retur.phone' => 'required',
    ];

    public function messages() //function untuk pesan error
    {
        return [
            'retur.company.required' => 'Nama perusahaan harus diisi.',
            'retur.name.required' => 'Nama supplier harus diisi.',
            'retur.phone.required' => 'Nomor Supplier harus diisi.',
        ];
    }

    public function updated($fields) //function dari livewire untuk real-time validation
    {
        $this->validateOnly($fields);
    }

    public function setSupplier($suppleirId)
    {
        $supplier = Supplier::find($suppleirId);
        $this->retur['supplier_id'] = $supplier->id;
        $this->retur['company'] = $supplier->company;
        $this->retur['name'] = $supplier->name;
        $this->retur['phone'] = $supplier->phone;
        $this->retur['address'] = $supplier->address;

        $this->validate();
    }

    public function addGood($good_id)
    {
        $good = Goods::find($good_id);
        $productKey = collect($this->goodReturs)->search(function ($item) use ($good) {
            return $item['goods_id'] == $good->id;
        });

        if ($productKey !== false) {
            $this->dispatchBrowserEvent('error', ['message' => 'Barang sudah terinput']);
        } else {
            $this->goodReturs[] = [
                'goods_id' => $good->id,
                'name' => $good->name,
                'category' => $good->category->name,
                'qty' => 1,
                'unit' => $good->unit,
            ];
        }
    }

    public function increment($index)
    {
        $this->goodReturs[$index]['qty'] += 1;
    }

    public function decrement($index)
    {
        if ($this->goodReturs[$index]['qty'] <= 1) {
            $this->goodReturs[$index]['qty'] = 1;
            $this->dispatchBrowserEvent('error', ['message' => 'Tidak bisa kurang dari 1']);
        } else {
            $this->goodReturs[$index]['qty'] -= 1;
        }
    }

    public function deleteGood($index)
    {
        unset($this->goodReturs[$index]);
        array_values($this->goodReturs);
    }

    public function save()
{
    $this->validate();

    // Simpan data retur
    $retur = retur::create([
        'supplier_id' => $this->retur['supplier_id'],
        'company' => $this->retur['company'],
        'name' => $this->retur['name'],
        'phone' => $this->retur['phone'],
        'address' => $this->retur['address'] ?? '',
    ]);

    foreach ($this->goodReturs as $good) {
        // Hubungkan retur dengan barang (attach ke pivot table)
        $retur->goods()->attach($good['goods_id'], [
            'qty' => $good['qty'],
        ]);

        // Decrement stok barang berdasarkan qty
        \App\Models\Goods::where('id', $good['goods_id'])->decrement('stock', $good['qty']);
    }

    if ($retur) {
        return redirect()->route('goods.management')->with('success', 'Pesanan berhasil!');
    }
}


    public function render()
    {
        $suppliers = Supplier::when($this->searchSupplier, function ($query) {
            $query->search($this->searchSupplier); // menjalankan query search
        })
            ->orderBy('created_at', 'desc')
            ->get();

        $goods = Goods::when($this->search, function ($query) {
            $query->search($this->search); // menjalankan query search
        })->when($this->byCategory, function ($query) {
            $query->where('category_id', $this->byCategory); // menjalankan query by Category
        })
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::all();

        return view('livewire.retur.create', compact('suppliers', 'goods', 'categories'));
    }
}
