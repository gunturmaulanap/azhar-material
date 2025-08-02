<?php

namespace App\Http\Livewire\Master;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash; // Untuk hash password
use Livewire\Component;

class CustomerForm extends Component
{
    public $form = 'create';
    public $customerId, $customer;
    public $type = 'password';


    public function togglePassword()
    {
        $this->type = $this->type === 'password' ? 'text' : 'password';
    }

    /**
     * Reset input form untuk data customer.
     */
    public function resetInput()
    {
        if ($this->customerId == null) {
            $this->customer = []; // Kosongkan jika tidak ada customerId
        } else {
            $this->setData(); // Isi data jika customerId ada
        }
    }

    /**
     * Aturan validasi dinamis menggunakan fungsi.
     * Memungkinkan validasi unik berdasarkan customerId.
     */
    public function getRules()
    {
        return [
            'customer.name' => 'required|min:3',
            'customer.phone' => 'required|min:10',
            'customer.username' => 'nullable|min:3|unique:customers,username,' . ($this->customerId ?? 'NULL'),
            'customer.password' => 'nullable|min:6',
        ];
    }

    /**
     * Pesan kesalahan untuk validasi input.
     */
    public function messages()
    {
        return [
            'customer.name.required' => 'Nama harus diisi.',
            'customer.name.min' => 'Panjang nama minimal adalah :min karakter.',
            'customer.phone.required' => 'Nomor telepon harus diisi.',
            'customer.phone.min' => 'Panjang nomor telepon minimal adalah :min karakter.',
            'customer.username.min' => 'Username minimal :min karakter.',
            'customer.username.unique' => 'Username sudah terpakai.',
            'customer.password.min' => 'Password minimal :min karakter.',
        ];
    }

    /**
     * Validasi real-time saat field diubah.
     */
    public function updated($fields)
    {
        $this->validateOnly($fields, $this->getRules());
    }

    /**
     * Simpan atau update data customer.
     */
    public function save()
    {
        // Validasi semua field saat submit
        $this->validate($this->getRules());

        if ($this->customerId) {
            // Jika $customerId ada, berarti sedang melakukan update data
            $customerData = $this->customer;

            // Jika password tidak diisi, jangan update field password
            if (empty($customerData['password'])) {
                unset($customerData['password']);
            } else {
                $customerData['password'] = Hash::make($customerData['password']); // Hash password
            }

            Customer::where('id', $this->customerId)->update($customerData);

            return redirect()->route('master.customer')->with('success', 'Data diubah!');
        } else {
            // Jika $customerId tidak ada, berarti sedang melakukan create data
            $this->customer['password'] = !empty($this->customer['password'])
                ? Hash::make($this->customer['password']) // Hash password saat create
                : null;

            Customer::create($this->customer);
            $this->customer = [];

            return redirect()->route('master.customer')->with('success', 'Data ditambahkan!');
        }
    }

    /**
     * Ambil data customer berdasarkan ID.
     */
    public function setData()
    {
        $data = Customer::findOrFail($this->customerId);
        $this->customer = [
            'name' => $data->name,
            'phone' => $data->phone,
            'address' => $data->address,
            'username' => $data->username, // Tambahkan username jika ada
        ];
    }

    /**
     * Lifecycle mount untuk menangkap parameter ID (jika ada).
     */
    public function mount($id = null)
    {
        if ($id !== null) {
            $this->customerId = $id;
            $this->form = 'update';
            $this->setData();
        }
    }

    /**
     * Render tampilan Livewire.
     */
    public function render()
    {
        return view('livewire.master.customer-form');
    }
}
