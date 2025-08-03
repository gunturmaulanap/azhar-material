<?php

namespace App\Http\Livewire\Team;

use App\Models\Team;
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
        $team = Team::findOrFail($id);
        $team->update(['is_active' => !$team->is_active]);
        session()->flash('message', 'Status berhasil diperbarui.');
    }

    public function delete($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();
        session()->flash('message', 'Data tim berhasil dihapus.');
    }

    public function render()
    {
        $teams = Team::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('position', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.team.index', [
            'teams' => $teams
        ]);
    }
}
