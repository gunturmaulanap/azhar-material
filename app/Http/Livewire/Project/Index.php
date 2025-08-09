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
            
            $this->emit('projectUpdated', 'Project featured status updated successfully!');
        }
    }

    public function toggleStatus($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            $project->status = $project->status === 'published' ? 'draft' : 'published';
            $project->save();
            
            $this->emit('projectUpdated', 'Project status updated successfully!');
        }
    }

    public function deleteProject($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            // Delete image if exists
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            
            $project->delete();
            $this->emit('projectDeleted', 'Project deleted successfully!');
        }
    }

    public function getProjectsProperty()
    {
        $query = Project::query();

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%')
                  ->orWhere('client', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply sorting
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
