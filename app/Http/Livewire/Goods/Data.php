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
        'confirm' => 'delete',   // dipanggil dari alert.blade (Livewire.emit('confirm', id))
        'perpage' => 'setPerPage',
    ];

    public $deleteId = null;
    public $deleteType = null;

    public function setPerPage($value)
    {
        $this->perPage = (int) $value ?: 10;
        $this->resetPage();
    }

    // Reset page saat filter berubah
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
        $this->deleteId   = $id;
        $this->deleteType = $type;

        // Panggil dialog konfirmasi yang didengar alert.blade.php
        $this->dispatchBrowserEvent('toast:confirm', [
            'id'      => $id,
            'title'   => 'Hapus data?',
            'message' => 'Tindakan ini tidak bisa dibatalkan.',
        ]);
    }

    // Akan dipanggil setelah user klik "YA" di dialog
    public function delete($id = null)
    {
        $id   = $id ?: $this->deleteId;
        $type = $this->deleteType ?: 'goods';

        $model = match ($type) {
            'goods'    => Goods::find($id),
            'brand'    => Brand::find($id),
            'category' => Category::find($id),
            default    => null,
        };

        if (!$model) {
            $this->dispatchBrowserEvent('toast:error', [
                'message' => 'Data tidak ditemukan!'
            ]);
            return;
        }

        $model->delete();

        $this->dispatchBrowserEvent('toast:warning', [
            'message' => 'Data terhapus!'
        ]);

        // bersihkan state & refresh
        $this->deleteId = null;
        $this->deleteType = null;
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();

        $data = Goods::with(['category:id,name', 'brand:id,name'])
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->byCategory, fn($q) => $q->where('category_id', $this->byCategory))
            ->when($this->byBrand, fn($q) => $q->where('brand_id', $this->byBrand))
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        return view('livewire.goods.data', compact('categories', 'brands', 'data'));
    }
}
