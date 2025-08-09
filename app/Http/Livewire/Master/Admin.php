<?php

namespace App\Http\Livewire\Master;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Admin extends Component
{
    use WithPagination; // Class dari livewire untuk fitur pagination

    public $search;
    public $perPage = 10;

    public function setPerPage($value)
    {
        $this->perPage = $value;
    }

    protected $listeners = [ // listeners handler untuk menjalankan delete setelah confirm
        'confirm' => 'delete',
        'perpage' => 'setPerPage',
    ];

    public function validationDelete($id) // function menjalankan confirm delete
    {
        $this->dispatchBrowserEvent('validation', [
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $deleted = User::find($id)->delete();

        if ($deleted) {
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function render()
    {
        // Get admin roles (excluding customer role)
        $adminRoles = ['admin', 'super_admin', 'content-admin', 'owner', 'driver'];

        $data = User::whereIn('role', $adminRoles)
            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->orderBy('name', 'asc') // ⬅️ Urutkan berdasarkan nama A-Z
            ->paginate($this->perPage);

        // Get available roles untuk dropdown
        $roles = Role::whereIn('name', $adminRoles)->get();

        return view('livewire.master.admin', [
            'data' => $data,
            'roles' => $roles,
        ]);
    }
}
