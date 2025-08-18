<?php

namespace App\Http\Livewire\Project;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // >>> TAMBAH: supaya konfirmasi dari JS bisa memanggil deleteProject
    protected $listeners = ['confirm' => 'deleteProject'];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleFeatured($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            $project->featured = !$project->featured;
            $project->save();

            // >>> GANTI: pakai browser event untuk iziToast
            $this->dispatchBrowserEvent('toast:success', [
                'message' => 'Project featured status updated.'
            ]);
        }
    }

    public function toggleStatus($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            $project->status = $project->status === 'published' ? 'draft' : 'published';
            $project->save();

            // >>> GANTI: pakai browser event untuk iziToast
            $this->dispatchBrowserEvent('toast:success', [
                'message' => 'Project status updated.'
            ]);
        }
    }

    // >>> BARU: tampilkan dialog konfirmasi via iziToast
    public function confirmDelete($projectId)
    {
        $this->dispatchBrowserEvent('toast:confirm', [
            'id'      => $projectId,
            'title'   => 'Hapus project?',
            'message' => 'Tindakan ini tidak bisa dibatalkan.'
        ]);
    }

    // Catatan: dipanggil dari JS dengan Livewire.emit('confirm', id)
    public function deleteProject($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $project->delete();

            // >>> Notifikasi hapus
            $this->dispatchBrowserEvent('toast:warning', [
                'message' => 'Project deleted.'
            ]);
        }
    }

    public function getProjectsProperty()
    {
        $query = Project::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('category', 'like', '%' . $this->search . '%')
                    ->orWhere('client', 'like', '%' . $this->search . '%')
                    ->orWhere('location', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(12);
    }

    public function render()
    {
        return view('livewire.project.index', [
            'projects' => $this->projects
        ]);
    }
}
