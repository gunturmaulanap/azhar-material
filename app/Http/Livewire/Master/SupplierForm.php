<?php

namespace App\Http\Livewire\Master;

use App\Models\Supplier;
use Livewire\Component;

class SupplierForm extends Component
{
    public $form = 'create';
    public $supplierId, $supplier;

    public function resetInput()
    {
        $this->supplier = $this->supplierId == null ? [] : $this->setData();
    }

    // untuk membuat peraturan validation
    protected $rules = [
        'supplier.company' => 'required|min:3',
        'supplier.name' => 'required|min:3',
        'supplier.phone' => 'required|min:10',
    ];

    public function messages() //function untuk pesan error
    {
        return [
            'supplier.company.required' => 'Nama perusahaan harus diisi.',
            'supplier.company.min' => 'Panjang nama perusahaan minimal adalah :min karakter.',
            'supplier.name.required' => 'Nama supplier harus diisi.',
            'supplier.name.min' => 'Panjang nama supplier minimal adalah :min karakter.',
            'supplier.phone.required' => 'Nomor telp harus diisi.',
            'supplier.phone.min' => 'Panjang nomor telp minimal adalah :min.',
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

        if ($this->supplierId) {
            // Jika $supplierId ada, berarti sedang melakukan update data
            Supplier::where('id', $this->supplierId)->update($this->supplier);

            return redirect()->route('master.supplier')->with('success', 'Data diubah!');
        } else {
            // Jika $supplierId tidak ada, berarti sedang melakukan create data
            Supplier::create($this->supplier);
            $this->supplier = [];

            return redirect()->route('master.supplier')->with('success', 'Data ditambahkan!');
        }
    }

    public function setData()
    {
        $data = Supplier::findOrFail($this->supplierId);
        $this->supplier = [
            'company' => $data->company,
            'name' => $data->name,
            'phone' => $data->phone,
            'address' => $data->address,
            'keterangan' => $data->keterangan,

        ];
    }

    public function mount($id = null)
    {
        if ($id !== null) {
            $this->form = 'update';
            $this->supplierId = $id;
            $this->setData();
        };
    }

    public function render()
    {
        return view('livewire.master.supplier-form');
    }
}
