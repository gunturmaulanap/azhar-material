<?php

namespace App\Http\Livewire\Goods;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Goods;
use Livewire\Component;
use Livewire\WithPagination;

class Data extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $byCategory = '';
    public $byBrand = '';
    public $perPage = 10;

    protected $listeners = [
        'confirm' => 'delete',
        'perpage' => 'setPerPage',
    ];

    public $deleteId;
    public $deleteType;

    public function setPerPage($value)
    {
        $this->perPage = (int) $value ?: 10;
        $this->resetPage();
    }

    /** Pastikan reset page saat input berubah */
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingByCategory()
    {
        $this->resetPage();
    }
    public function updatingByBrand()
    {
        $this->resetPage();
    }
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function validationDelete($id, $type)
    {
        $this->deleteId = $id;
        $this->deleteType = $type;
        $this->dispatchBrowserEvent('validation');
    }

    public function delete()
    {
        $model = match ($this->deleteType) {
            'goods'    => \App\Models\Goods::find($this->deleteId),
            'brand'    => \App\Models\Brand::find($this->deleteId),
            'category' => \App\Models\Category::find($this->deleteId),
            default    => null,
        };

        if (!$model) {
            $this->dispatchBrowserEvent('not-found');
            return;
        }

        $model->delete();

        $this->dispatchBrowserEvent('deleted');
        $this->resetPage();
    }

    public function render()
    {
        // Dropdown sumber
        $categories = Category::orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();

        // Data tabel (eager-load supaya aman saat akses relasi)
        $data = Goods::with(['category:id,name', 'brand:id,name'])
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->byCategory, fn($q) => $q->where('category_id', $this->byCategory))
            ->when($this->byBrand, fn($q) => $q->where('brand_id', $this->byBrand))
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        return view('livewire.goods.data', compact('categories', 'brands', 'data'));
    }
}
