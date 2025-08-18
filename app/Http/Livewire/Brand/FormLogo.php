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

        try {
            $brand = $this->isEdit
                ? Brand::findOrFail($this->brandId)
                : new Brand();

            // data dasar
            $brand->name        = $this->name;
            $brand->website_url = $this->website_url;
            $brand->is_active   = $this->is_active ?? true;

            // handle logo
            if ($this->logo) {
                // hapus file lama jika ada
                if ($brand->logo && \Storage::disk('public')->exists($brand->logo)) {
                    \Storage::disk('public')->delete($brand->logo);
                }
                $brand->logo = $this->logo->store('brands', 'public');
            } elseif (!$this->isEdit) {
                // create tanpa upload
                $brand->logo = null;
            }

            $brand->save();

            // sukses: flash untuk ditampilkan di halaman index via iziToast
            session()->flash(
                'success',
                $this->isEdit
                    ? 'Brand berhasil diperbarui.'
                    : 'Brand berhasil dibuat.'
            );

            // Livewire v2 → redirect server-side (langsung pindah halaman)
            return redirect()->route('content-admin.brand.index');
        } catch (\Throwable $e) {
            report($e);

            // gagal: tampilkan toast di halaman ini (tanpa redirect)
            $this->dispatchBrowserEvent('toast', [
                'type'    => 'error',
                'title'   => 'Gagal',
                'message' => 'Terjadi kesalahan saat menyimpan brand.'
            ]);

            return null;
        }
    }

    public function render()
    {
        return view('livewire.brand.form-logo')
            ->layout('layouts.app');
    }
}
