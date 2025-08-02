<?php

namespace App\Http\Livewire\Category;

use App\Models\Category;
use Livewire\Component;

class Form extends Component
{
    public $form = 'create';
    public $categoryId, $category;

    public function resetInput()
    {
        $this->category = $this->categoryId == null ? [] : $this->setData();
    }

    // untuk membuat peraturan validation
    protected $rules = [
        'category.name' => 'required|min:3',
    ];

    public function messages() //function untuk pesan error
    {
        return [
            'category.name.required' => 'Nama kategori harus diisi.',
            'category.name.min' => 'Panjang nama kategori minimal adalah :min karakter.',
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

        if ($this->categoryId) {
            // Jika $categoryId ada, berarti sedang melakukan update data
            Category::where('id', $this->categoryId)->update($this->category);

            return redirect()->route('goods.data')->with('success', 'Data diubah!');
        } else {
            // Jika $categoryId tidak ada, berarti sedang melakukan create data
            Category::create($this->category);
            $this->category = [];

            return redirect()->route('goods.data')->with('success', 'Data ditambahkan!');
        }
    }

    public function setData()
    {
        $data = Category::findOrFail($this->categoryId);
        $this->category = [
            'name' => $data->name,
            'desc' => $data->desc,
        ];
    }

    public function mount($id = null)
    {
        if ($id !== null) {
            $this->categoryId = $id;
            $this->form = 'update';
            $this->setData();
        };
    }

    public function render()
    {
        return view('livewire.category.form');
    }
}
