<?php

namespace App\Http\Livewire\Goods;

use App\Models\Brand;
use App\Models\Category;

use App\Models\Goods;
use Livewire\Component;
use Livewire\WithPagination;

class Data extends Component
{
    use WithPagination; // Class dari livewire untuk fitur pagination

    public $search, $byCategory, $byBrand;

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
        $deleted = Goods::find($id)->delete();

        if ($deleted) {
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function render()
    {
        // Mendapatkan daftar semua kategori dan mengurutkan berdasarkan nama A-Z
        $categories = Category::orderBy('name', 'asc')->get();

        // Mendapatkan daftar semua Brand dan mengurutkan berdasarkan nama A-Z
        $brands = Brand::orderBy('name', 'asc')->get();

        // Query data berdasarkan pencarian dan filter
        $data = Goods::when($this->search, function ($query) {
            $query->search($this->search); // Menjalankan query pencarian
        })
            ->when($this->byCategory, function ($query) {
                $query->where('category_id', $this->byCategory); // Menjalankan query filter berdasarkan kategori
            })
            ->when($this->byBrand, function ($query) {
                $query->where('brand_id', $this->byBrand); // Menjalankan query filter berdasarkan brand
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        // Mengembalikan data ke view
        return view('livewire.goods.data', [
            'categories' => $categories,
            'brands' => $brands,
            'data' => $data,
        ]);
    }
}
