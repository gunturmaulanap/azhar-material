<?php

namespace App\Http\Livewire\Goods;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Goods;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $form = 'create';
    public $goodId, $good;

    public function resetInput()
    {
        $this->good = $this->goodId == null ? [] : $this->setData();
    }

    // untuk membuat peraturan validation
    protected function rules()
    {
        // Khusus content-admin hanya boleh upload gambar
        if (auth()->user()->role === 'content-admin') {
            return [
                'good.image' => 'required|image|max:2048', // maks 2MB
            ];
        }

        $rules = [
            'good.name' => 'required|min:3',
            'good.category_id' => 'required',
            'good.brand_id' => 'required',
            'good.unit' => 'required',
            'good.cost' => 'required',
            'good.price' => 'required',
        ];

        // Add stock validation only for owner
        if (auth()->user()->role === 'owner') {
            $rules['good.stock'] = 'required|numeric|min:0';
        }

        return $rules;
    }

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
            'good.stock.required' => 'Stok harus diisi.',
            'good.stock.numeric' => 'Stok harus berupa angka.',
            'good.stock.min' => 'Stok tidak boleh kurang dari 0.',
            'good.image.required' => 'Gambar harus diunggah.',
            'good.image.image' => 'File harus berupa gambar (jpeg, png, bmp, gif, svg, atau webp).',
            'good.image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    public function updated($fields) //function dari livewire untuk real-time validation
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        // Jika content-admin: hanya boleh unggah gambar untuk barang yang sudah ada
        if (auth()->user()->role === 'content-admin') {
            $this->validate();

            if (!$this->goodId) {
                session()->flash('error', 'Content-admin hanya dapat mengunggah gambar untuk data yang sudah ada.');
                return; // Tidak lanjut jika tidak ada ID barang
            }

            // Simpan file
            $path = $this->good['image']->store('goods', 'public');

            Goods::where('id', $this->goodId)->update([
                'image' => $path,
            ]);

            return redirect()->route(str_replace('_', '', auth()->user()->role) . '.goods.data')
                ->with('success', 'Gambar berhasil diperbarui!');
        }

        // Validasi semua field saat submit untuk role lain
        $this->validate();

        // Pastikan content non-gambar tidak bisa diubah lewat injeksi
        unset($this->good['image']);

        if ($this->goodId) {
            Goods::where('id', $this->goodId)->update($this->good);
            return redirect()->route(str_replace('_', '', auth()->user()->role) . '.goods.data')->with('success', 'Data diubah!');
        } else {
            Goods::create($this->good);
            $this->good = [];
            return redirect()->route(str_replace('_', '', auth()->user()->role) . '.goods.data')->with('success', 'Data ditambahkan!');
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

        // Tampilkan stok hanya untuk owner
        if (auth()->user()->role === 'owner') {
            $this->good['stock'] = $data->stock ?? 0;
        }

        // Untuk content-admin, tampilkan path gambar saat ini (read-only) untuk informasi/pratinjau
        if (auth()->user()->role === 'content-admin') {
            $this->good['image_path'] = $data->image;
        }
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
