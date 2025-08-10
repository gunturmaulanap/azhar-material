<?php

namespace App\Http\Livewire\Brand;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Brand;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class FormLogo extends Component
{
    use WithFileUploads;

    public $brandId;
    public $name, $logo, $website_url, $is_active; // Properti $description sudah dihapus
    public $isEdit = false;

    public $existing_logo_path;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            // Aturan validasi untuk 'description' sudah dihapus
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
            'website_url' => 'nullable|url|max:255',
            'is_active' => 'boolean'
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->brandId = $id;
            $this->isEdit = true;
            $this->loadBrand();
        } else {
            $this->is_active = true;
        }
    }

    public function loadBrand()
    {
        $brand = Brand::findOrFail($this->brandId);
        $this->name = $brand->name;
        // Inisialisasi untuk 'description' sudah dihapus
        $this->website_url = $brand->website_url;
        $this->is_active = $brand->is_active;
        $this->existing_logo_path = $brand->logo;
    }

    public function save()
    {
        $this->validate($this->rules());

        $data = [
            'name' => $this->name,
            // 'description' sudah dihapus dari array data
            'website_url' => $this->website_url,
            'is_active' => $this->is_active ?? true,
        ];

        if ($this->isEdit) {
            $brand = Brand::findOrFail($this->brandId);

            if ($this->logo) {
                if ($brand->logo) {
                    Storage::disk('public')->delete($brand->logo);
                }
                $data['logo'] = $this->logo->store('brands', 'public');
            } else {
                $data['logo'] = $brand->logo;
            }

            $brand->update($data);
            session()->flash('message', 'Brand berhasil diperbarui.');
        } else {
            if ($this->logo) {
                $data['logo'] = $this->logo->store('brands', 'public');
            } else {
                $data['logo'] = null;
            }

            Brand::create($data);
            session()->flash('message', 'Brand berhasil dibuat.');
        }

        return redirect()->route('content-admin.brand.index');
    }

    public function render()
    {
        return view('livewire.brand.form-logo')
            ->layout('layouts.app');
    }
}
