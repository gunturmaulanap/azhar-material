<?php

namespace App\Http\Livewire\Brand;

use App\Models\Brand;
use Livewire\Component;

class Form extends Component
{
    public $form = 'create';
    public $brandId, $brand;

    public function resetInput()
    {
        $this->brand = $this->brandId == null ? [] : $this->setData();
    }

    // untuk membuat peraturan validation
    protected $rules = [
        'brand.name' => 'required|min:3',
    ];

    public function messages() //function untuk pesan error
    {
        return [
            'brand.name.required' => 'Nama kategori harus diisi.',
            'brand.name.min' => 'Panjang nama kategori minimal adalah :min karakter.',
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

        if ($this->brandId) {
            // Jika $brandId ada, berarti sedang melakukan update data
            Brand::where('id', $this->brandId)->update($this->brand);

            return redirect()->route('goods.data')->with('success', 'Data diubah!');
        } else {
            // Jika $brandId tidak ada, berarti sedang melakukan create data
            Brand::create($this->brand);
            $this->brand = [];

            return redirect()->route('goods.data')->with('success', 'Data ditambahkan!');
        }
    }

    public function setData()
    {
        $data = Brand::findOrFail($this->brandId);
        $this->brand = [
            'name' => $data->name,
            'desc' => $data->desc,

        ];
    }

    public function mount($id = null)
    {
        if ($id !== null) {
            $this->brandId = $id;
            $this->form = 'update';
            $this->setData();
        };
    }

    public function render()
    {
        return view('livewire.brand.form');
    }
}

