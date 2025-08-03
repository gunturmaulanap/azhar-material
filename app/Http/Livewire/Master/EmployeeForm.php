<?php

namespace App\Http\Livewire\Master;

use App\Models\Employee;
use Livewire\Component;

class EmployeeForm extends Component
{
    public $form = 'create';
    public $employeeId, $employee;

    public function resetInput()
    {
        $this->employee = $this->employeeId == null ? [] : $this->setData();
    }

    // untuk membuat peraturan validation
    protected $rules = [
        'employee.name' => 'required|min:3',
        'employee.phone' => 'required|min:10',
        'employee.position' => 'required',
        'employee.active' => 'required',
    ];

    public function messages() //function untuk pesan error
    {
        return [
            'employee.name.required' => 'Nama harus diisi.',
            'employee.name.min' => 'Panjang nama minimal adalah :min karakter.',
            'employee.phone.required' => 'Nomor telp harus diisi.',
            'employee.phone.min' => 'Panjang nomor telp minimal adalah :min karakter.',
            'employee.position.required' => 'Jabatan harus diisi.',
            'employee.active.required' => 'Status harus diisi.',
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

        if ($this->employeeId) {
            // Jika $employeeId ada, berarti sedang melakukan update data
            Employee::where('id', $this->employeeId)->update($this->employee);

            return redirect()->route('master.employee')->with('success', 'Data diubah!');
        } else {
            // Jika $employeeId tidak ada, berarti sedang melakukan create data
            Employee::create($this->employee);
            $this->employee = [];

            return redirect()->route('master.employee')->with('success', 'Data ditambahkan!');
        }
    }

    public function setData()
    {
        $data = Employee::findOrFail($this->employeeId);
        $this->employee = [
            'name' => $data->name,
            'phone' => $data->phone,
            'position' => $data->position,
            'active' => $data->active,
        ];
    }

    public function mount($id = null)
    {
        if ($id !== null) {
            $this->employeeId = $id;
            $this->form = 'update';
            $this->setData();
        };
    }

    public function render()
    {
        return view('livewire.master.employee-form');
    }
}
