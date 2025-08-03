<?php

namespace App\Http\Livewire\Service;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'order';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'order'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);
        $service->update(['is_active' => !$service->is_active]);
        session()->flash('message', 'Status berhasil diperbarui.');
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        session()->flash('message', 'Data layanan berhasil dihapus.');
    }

    public function render()
    {
        $services = Service::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.service.index', [
            'services' => $services
        ]);
    }
}
