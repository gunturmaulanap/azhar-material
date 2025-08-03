<?php

namespace App\Http\Livewire\About;

use App\Models\About;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $about = About::findOrFail($id);
        $about->update(['is_active' => !$about->is_active]);
        session()->flash('message', 'Status berhasil diperbarui.');
    }

    public function delete($id)
    {
        $about = About::findOrFail($id);
        $about->delete();
        session()->flash('message', 'Data about berhasil dihapus.');
    }

    public function render()
    {
        $abouts = About::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('company_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.about.index', [
            'abouts' => $abouts
        ]);
    }
}
