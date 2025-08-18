<?php

namespace App\Http\Livewire\Goods;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Goods;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $form = 'create';
    public $goodId;
    public $good = [];

    /* ----------------------------- UI helpers ----------------------------- */

    public function resetInput()
    {
        // kalau sedang edit -> muat ulang dari DB, kalau create -> kosongkan
        $this->good = $this->goodId ? $this->setData() : [];
    }

    /* ------------------------------- Rules -------------------------------- */

    protected function rules()
    {
        $role = auth()->user()->role;

        // Khusus content-admin: hanya upload gambar
        if ($role === 'content-admin') {
            return [
                'good.image' => 'required|image|max:2048', // 2MB
            ];
        }

        // super_admin, admin, owner: isi form lengkap (tanpa image)
        $rules = [
            'good.name'        => 'required|string|min:3|max:255',
            'good.category_id' => 'required|exists:categories,id',
            'good.brand_id'    => 'required|exists:brands,id',
            'good.unit'        => 'required|string|max:50',
            'good.cost'        => 'required|numeric|min:0',
            'good.price'       => 'required|numeric|min:0',
        ];

        // stok hanya dipakai owner
        if ($role === 'owner') {
            $rules['good.stock'] = 'required|integer|min:0';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'good.name.required'        => 'Nama barang harus diisi.',
            'good.name.min'             => 'Panjang nama minimal :min karakter.',
            'good.category_id.required' => 'Kategori harus dipilih.',
            'good.category_id.exists'   => 'Kategori tidak valid.',
            'good.brand_id.required'    => 'Brand harus dipilih.',
            'good.brand_id.exists'      => 'Brand tidak valid.',
            'good.unit.required'        => 'Satuan harus diisi.',
            'good.cost.required'        => 'Harga beli harus diisi.',
            'good.cost.numeric'         => 'Harga beli harus angka.',
            'good.price.required'       => 'Harga jual harus diisi.',
            'good.price.numeric'        => 'Harga jual harus angka.',
            'good.stock.required'       => 'Stok harus diisi.',
            'good.stock.integer'        => 'Stok harus berupa bilangan bulat.',
            'good.stock.min'            => 'Stok tidak boleh kurang dari 0.',
            'good.image.required'       => 'Gambar harus diunggah.',
            'good.image.image'          => 'File harus gambar.',
            'good.image.max'            => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    // Validasi realtime field yang berubah
    public function updated($field)
    {
        // hanya validasi field tersebut berdasarkan rules() dinamis
        $this->validateOnly($field);
    }

    /* -------------------------------- Save -------------------------------- */

    public function save()
    {
        $role = auth()->user()->role;

        /* -------- content-admin: hanya update image untuk item existing ------ */
        if ($role === 'content-admin') {
            $this->validate([
                'good.image' => 'required|image|max:2048',
            ]);

            if (!$this->goodId) {
                // tidak boleh bikin item baru
                return redirect()
                    ->route('content-admin.goods.data')
                    ->with('error', 'Content-admin hanya dapat mengunggah gambar untuk data yang sudah ada.');
            }

            $path = $this->good['image']->store('goods', 'public');

            Goods::whereKey($this->goodId)->update([
                'image' => $path,
            ]);

            return redirect()
                ->route('content-admin.goods.data')
                ->with('success', 'Gambar berhasil diperbarui!');
        }

        /* -------- super_admin / admin / owner: simpan seluruh field ---------- */
        $this->validate(); // pakai rules() dinamis

        // Ambil hanya kolom yang boleh disimpan
        $payload = Arr::only(
            $this->good,
            ['name', 'category_id', 'brand_id', 'unit', 'cost', 'price', 'stock']
        );

        // Jika bukan owner, jangan kirim 'stock'
        if ($role !== 'owner') {
            unset($payload['stock']);
        }

        // Pastikan tidak ada 'image' atau 'image_path' yang ikut
        unset($payload['image'], $payload['image_path']);

        if ($this->goodId) {
            Goods::whereKey($this->goodId)->update($payload);
            $msg = 'Data diubah!';
        } else {
            $created = Goods::create($payload);
            $this->goodId = $created->id;
            $msg = 'Data ditambahkan!';
        }

        // Prefix route: super_admin -> superadmin, dsb.
        $prefix = str_replace('_', '', $role);

        return redirect()
            ->route($prefix . '.goods.data')
            ->with('success', $msg);
    }

    /* ------------------------------ Utilities ----------------------------- */

    public function setData(): array
    {
        $data = Goods::findOrFail($this->goodId);

        $good = [
            'name'        => $data->name,
            'category_id' => $data->category_id,
            'brand_id'    => $data->brand_id,
            'unit'        => $data->unit,
            'cost'        => $data->cost,
            'price'       => $data->price,
        ];

        if (auth()->user()->role === 'owner') {
            $good['stock'] = $data->stock ?? 0;
        }

        if (auth()->user()->role === 'content-admin') {
            $good['image_path'] = $data->image; // untuk preview di form
        }

        return $this->good = $good;
    }

    public function mount($id = null)
    {
        if ($id !== null) {
            $this->goodId = $id;
            $this->form   = 'update';
            $this->setData();
        }
    }

    public function render()
    {
        return view('livewire.goods.form', [
            'brands'     => Brand::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}
