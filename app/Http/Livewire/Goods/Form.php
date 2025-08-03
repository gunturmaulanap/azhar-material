<?php

namespace App\Http\Livewire\Goods;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Goods;
use Livewire\Component;

class Form extends Component
{
    public $form = 'create';
    public $goodId, $good;

    public function resetInput()
    {
        $this->good = $this->goodId == null ? [] : $this->setData();
    }

    // untuk membuat peraturan validation
    protected $rules = [
        'good.name' => 'required|min:3',
        'good.category_id' => 'required',
        'good.brand_id' => 'required',

        'good.unit' => 'required',
        'good.cost' => 'required',
        'good.price' => 'required',
    ];

    public function messages() //function untuk pesan error
    {
        return [
            'good.name.required' => 'Nama barang harus diisi.',
            'good.name.min' => 'Panjang nama barang minimal adalah :min karakter.',
            'good.category_id.required' => 'Kategori harus diisi.',
            'good.brand_id.required' => 'Kategori harus diisi.',

            'good.unit.required' => 'Satuan harus diisi.',
            'good.cost.required' => 'Harga beli harus diisi.',
            'good.price.required' => 'Harga jual harus diisi.',
        ];
    }

    public function updated($fields) //function dari livewire untuk real-time validation
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        // Validasi semua field saat submit
        $this->validate();

        if ($this->goodId) {
            // Jika $goodId ada, berarti sedang melakukan update data
            Goods::where('id', $this->goodId)->update($this->good);

            return redirect()->route('goods.data')->with('success', 'Data diubah!');
        } else {
            // Jika $goodId tidak ada, berarti sedang melakukan create data
            Goods::create($this->good);
            $this->good = [];

            return redirect()->route('goods.data')->with('success', 'Data ditambahkan!');
        }
    }

    public function setData()
    {
        $data = Goods::findOrFail($this->goodId);
        $this->good = [
            'name' => $data->name,
            'category_id' => $data->category_id,
            'brand_id' => $data->brand_id,

            'unit' => $data->unit,
            'cost' => $data->cost,
            'price' => $data->price,
        ];
    }

    public function mount($id = null)
    {
        if ($id !== null) {
            $this->goodId = $id;
            $this->form = 'update';
            $this->setData();
        };
    }

    public function render()
    {
        $categories = Category::all();
        $brands = Brand::all();


        return view('livewire.goods.form', [
            'brands' => Brand::orderBy('name', 'asc')->get(),
            'categories' => Category::orderBy('name', 'asc')->get(),

        ]);
    }
}
