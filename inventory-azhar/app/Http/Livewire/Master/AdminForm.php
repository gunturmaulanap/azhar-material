<?php

namespace App\Http\Livewire\Master;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class AdminForm extends Component
{
    public $form = 'create';
    public $type = 'password';

    public $userId, $oldPassword, $user;

    public function togglePassword()
    {
        $this->type = $this->type === 'password' ? 'text' : 'password';
    }

    public function resetInput()
    {
        $this->user = $this->userId == null ? [] : $this->setData();
    }

    protected function rules() //function untuk membuat peraturan validation
    {
        $rules = [
            //rule untuk create data
            'user.name' => 'required|min:3',
            'user.role' => 'required',
            'user.username' => 'required|min:3|unique:users,username',
            'user.password' => 'required|min:6',
        ];

        if ($this->userId) { // Jika update data
            $rules['user.username'] = 'required|unique:users,username,' . $this->userId;
            $rules['user.role'] = 'required';
            $rules['user.password'] = 'nullable|min:6'; // password boleh kosong saat update
        }

        return $rules;
    }

    public function messages() //function untuk pesan error
    {
        return [
            'user.name.required' => 'Nama harus diisi.',
            'user.name.min' => 'Panjang nama minimal adalah :min karakter.',
            'user.role.required' => 'Role email harus diisi.',
            'user.username.required' => 'Username harus diisi.',
            'user.username.unique' => 'Username ini sudah ada.',
            'user.username.min' => 'Panjang username minimal adalah :min karakter.',
            'user.password.required' => 'Password harus diisi.',
            'user.password.min' => 'Panjang password minimal adalah :min karakter.',
        ];
    }

    public function updated($fields) //function dari livewire untuk real-time validation
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        // Validasi semua field saat submit
        $this->validate($this->rules());

        if ($this->userId) {
            // Jika $userId ada, berarti sedang melakukan update data
            $user = User::findOrFail($this->userId);
            $user->name = $this->user['name'];
            $user->role = $this->user['role'];
            $user->username = $this->user['username'];

            // Jika password diisi, maka ubah password-nya
            if (!empty($this->user['password'])) {
                $user->password = Hash::make($this->user['password']);
            }

            $user->save();

            return redirect()->route('master.admin')->with('success', 'Data diubah!');
        } else {
            // Jika $userId tidak ada, berarti sedang melakukan create data
            User::create([
                'name' => $this->user['name'],
                'username' => $this->user['username'],
                'role' => $this->user['role'],
                'password' => Hash::make($this->user['password']),
            ]);

            return redirect()->route('master.admin')->with('success', 'Data ditambahkan!');
        }
    }

    public function setData()
    {
        $data = User::findOrFail($this->userId);
        $this->user = [
            'name' => $data->name,
            'role' => $data->role,
            'username' => $data->username,
        ];
    }

    public function mount($id = null)
    {
        if ($id !== null) {
            $this->form = 'update';
            $this->userId = $id;
            $this->setData();
        };
    }

    public function render()
    {
        return view('livewire.master.admin-form');
    }
}
