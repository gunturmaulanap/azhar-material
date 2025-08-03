<?php

namespace App\Http\Livewire\Brand;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Brand;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $name, $description, $logo, $website_url, $is_active;
    public $isEdit = false;
    public $brandId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'website_url' => 'nullable|url|max:255',
        'is_active' => 'boolean'
    ];

    public function render()
    {
        $brands = Brand::orderBy('name', 'asc')->paginate(10);
        return view('livewire.brand.index', compact('brands'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'website_url' => $this->website_url,
            'is_active' => $this->is_active ?? true,
        ];

        if ($this->logo) {
            $data['logo'] = $this->logo->store('brands', 'public');
        }

        Brand::create($data);

        session()->flash('message', 'Brand created successfully.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        $this->brandId = $brand->id;
        $this->name = $brand->name;
        $this->description = $brand->description;
        $this->website_url = $brand->website_url;
        $this->is_active = $brand->is_active;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        $brand = Brand::findOrFail($this->brandId);

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'website_url' => $this->website_url,
            'is_active' => $this->is_active ?? true,
        ];

        if ($this->logo) {
            // Delete old logo
            if ($brand->logo) {
                \Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $this->logo->store('brands', 'public');
        }

        $brand->update($data);

        session()->flash('message', 'Brand updated successfully.');
        $this->resetForm();
    }

    public function delete($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->logo) {
            \Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();
        session()->flash('message', 'Brand deleted successfully.');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->logo = null;
        $this->website_url = '';
        $this->is_active = true;
        $this->brandId = null;
        $this->isEdit = false;
    }
}
