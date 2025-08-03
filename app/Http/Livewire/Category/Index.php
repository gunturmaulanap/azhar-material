<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            $category = Category::findOrFail($id);
            
            // Check if category has goods
            if ($category->goods()->count() > 0) {
                session()->flash('error', 'Kategori tidak dapat dihapus karena masih memiliki barang.');
                return;
            }
            
            $category->delete();
            session()->flash('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus kategori.');
        }
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.category.index', compact('categories'))
            ->layout('layouts.app');
    }
}