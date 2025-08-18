<?php

namespace App\Http\Livewire\Project;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Form extends Component
{
    use WithFileUploads;

    public $projectId;
    public $project = [];

    public $title = '';
    public $description = '';
    public $category = '';
    public $client = '';
    public $location = '';
    public $year;
    public $status = 'draft';
    public $featured = false;
    public $sort_order = 0;

    public $image;                // upload file sementara
    public $existing_image;       // path lokal dari DB
    public $external_image_url;   // URL eksternal
    public $useExternalImage = false;

    public $isEditing = false;

    protected function rules()
    {
        $maxYear = date('Y') + 10;
        $rules = [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'nullable|string|max:255',
            'client'      => 'nullable|string|max:255',
            'location'    => 'nullable|string|max:255',
            'year'        => 'nullable|integer|min:1900|max:' . $maxYear,
            'status'      => 'required|in:draft,published',
            'featured'    => 'boolean',
            'sort_order'  => 'integer|min:0',
        ];

        if ($this->useExternalImage) {
            $rules['external_image_url'] = 'required|url|max:2048';
            $rules['image'] = 'nullable';
        } else {
            $rules['image'] = Rule::requiredIf(!$this->isEditing || ($this->isEditing && !$this->existing_image));
            $rules['image'] .= '|nullable|image|max:2048';
            $rules['external_image_url'] = 'nullable';
        }

        return $rules;
    }

    protected function messages()
    {
        $maxYear = date('Y') + 10;
        return [
            'title.required'               => 'Project title is required.',
            'description.required'         => 'Project description is required.',
            'external_image_url.required'  => 'External image URL is required when using this option.',
            'external_image_url.url'       => 'Please enter a valid URL.',
            'image.image'                  => 'The uploaded file must be an image.',
            'image.max'                    => 'The image size must be less than 2MB.',
            'image.required'               => 'An image file is required.',
            'year.min'                     => 'Year must be at least 1900.',
            'year.max'                     => 'Year cannot be more than ' . $maxYear . '.',
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->projectId = $id;
            $this->isEditing = true;
            $this->loadProject();
        } else {
            $this->year = date('Y');
        }
    }

    public function loadProject()
    {
        $project = Project::findOrFail($this->projectId);

        $this->title       = $project->title;
        $this->description = $project->description;
        $this->category    = $project->category ?? '';
        $this->client      = $project->client ?? '';
        $this->location    = $project->location ?? '';
        $this->year        = $project->year;
        $this->status      = $project->status;
        $this->featured    = $project->featured;
        $this->sort_order  = $project->sort_order;

        if ($project->external_image_url) {
            $this->useExternalImage   = true;
            $this->external_image_url = $project->external_image_url;
            $this->existing_image     = null;
        } else {
            $this->useExternalImage   = false;
            $this->external_image_url = null;
            $this->existing_image     = $project->image;
        }
    }
    public function resetInput()
    {
        // kalau sedang edit -> muat ulang dari DB, kalau create -> kosongkan
        $this->project = $this->projectId ? $this->setData() : [];
    }

    public function updatedUseExternalImage($value)
    {
        if ($value) {
            $this->reset('image');
        } else {
            $this->reset('external_image_url');
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $project = $this->isEditing
                ? Project::findOrFail($this->projectId)
                : new Project();

            // Kelola gambar
            if ($this->useExternalImage) {
                if ($project->image && Storage::disk('public')->exists($project->image)) {
                    Storage::disk('public')->delete($project->image);
                }
                $project->image = null;
                $project->external_image_url = $this->external_image_url;
            } else {
                $project->external_image_url = null;

                if ($this->image) {
                    if ($this->isEditing && $project->image && Storage::disk('public')->exists($project->image)) {
                        Storage::disk('public')->delete($project->image);
                    }
                    $project->image = $this->image->store('projects', 'public');
                } elseif ($this->isEditing && !$this->existing_image && $project->image) {
                    Storage::disk('public')->delete($project->image);
                    $project->image = null;
                }
            }

            // Simpan data lain
            $project->fill([
                'title'       => $this->title,
                'description' => $this->description,
                'category'    => $this->category ?: null,
                'client'      => $this->client ?: null,
                'location'    => $this->location ?: null,
                'year'        => $this->year,
                'status'      => $this->status,
                'featured'    => (bool) $this->featured,
                'sort_order'  => (int) $this->sort_order,
            ])->save();

            // Flash untuk iziToast di halaman index
            session()->flash(
                'success',
                $this->isEditing
                    ? 'Project updated successfully!'
                    : 'Project created successfully!'
            );

            // LIVEWIRE v2: redirect server-side (langsung pindah halaman)
            return redirect()->route('content-admin.projects');
        } catch (\Throwable $e) {
            report($e);
            // kalau gagal, tetap di halaman & tampilkan toast error
            session()->flash('error', 'An error occurred while saving the project.');
            return null;
        }
    }

    public function removeImage()
    {
        if ($this->isEditing && $this->existing_image) {
            $project = Project::findOrFail($this->projectId);
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
                $project->image = null;
                $project->save();
                $this->existing_image = null;
                $this->image = null;

                // ====> IZITOAST: info sukses hapus gambar
                $this->dispatchBrowserEvent('toast:success', [
                    'message' => 'Image removed successfully!'
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.project.form');
    }
}
