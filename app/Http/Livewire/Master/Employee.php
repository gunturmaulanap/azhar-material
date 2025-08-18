<?php

namespace App\Http\Livewire\Master;

use App\Models\Employee as ModelsEmployee;
use Livewire\Component;
use Livewire\WithPagination;

class Employee extends Component
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
    public function validationDelete($id)
    {
        $this->dispatchBrowserEvent('toast:confirm', [
            'id'      => $id,
            'title'   => 'Hapus pegawai?',
            'message' => 'Akun pegawai akan dihapus. Lanjutkan?',
        ]);
    }

    public function delete($id)
    {
        $user = ModelsEmployee::find($id);

        if (!$user) {
            $this->dispatchBrowserEvent('toast:error', ['message' => 'Data tidak ditemukan!']);
            return;
        }

        $deleted = $user->delete();

        if ($deleted) {
            $this->dispatchBrowserEvent('toast:warning', ['message' => 'Data terhapus!']);
            $this->resetPage();
        } else {
            $this->dispatchBrowserEvent('toast:error', ['message' => 'Gagal menghapus data.']);
        }
    }


    public function render()
    {
        $data = ModelsEmployee::when($this->search, function ($query) {
            $query->search($this->search);
        })
            ->orderBy('name', 'asc') // ⬅️ Urutkan berdasarkan nama A-Z
            ->paginate($this->perPage);

        return view('livewire.master.employee', [
            'data' => $data,
        ]);
    }
}
