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
    public $title = '';
    public $description = '';
    public $category = '';
    public $client = '';
    public $location = '';
    public $year;
    public $status = 'draft';
    public $featured = false;
    public $sort_order = 0;

    // Properti untuk gambar
    public $image; // Temporary file upload
    public $existing_image; // Path local dari DB
    public $external_image_url; // URL eksternal
    public $useExternalImage = false; // Toggle switch state

    public $isEditing = false;

    protected function rules()
    {
        $maxYear = date('Y') + 10;
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:255',
            'client' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . $maxYear,
            'status' => 'required|in:draft,published',
            'featured' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];

        // Validasi kondisional untuk gambar
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
            'title.required' => 'Project title is required.',
            'description.required' => 'Project description is required.',
            'external_image_url.required' => 'External image URL is required when using this option.',
            'external_image_url.url' => 'Please enter a valid URL.',
            'image.image' => 'The uploaded file must be an image.',
            'image.max' => 'The image size must be less than 2MB.',
            'image.required' => 'An image file is required.',
            'year.min' => 'Year must be at least 1900.',
            'year.max' => 'Year cannot be more than ' . $maxYear . '.',
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

        $this->title = $project->title;
        $this->description = $project->description;
        $this->category = $project->category ?? '';
        $this->client = $project->client ?? '';
        $this->location = $project->location ?? '';
        $this->year = $project->year;
        $this->status = $project->status;
        $this->featured = $project->featured;
        $this->sort_order = $project->sort_order;

        // Cek apakah gambar yang ada adalah URL eksternal
        if ($project->external_image_url) {
            $this->useExternalImage = true;
            $this->external_image_url = $project->external_image_url;
            $this->existing_image = null;
        } else {
            $this->useExternalImage = false;
            $this->external_image_url = null;
            $this->existing_image = $project->image;
        }
    }

    public function updatedUseExternalImage($value)
    {
        if ($value) {
            $this->reset('image'); // Hapus file yang diunggah jika beralih ke URL eksternal
        } else {
            $this->reset('external_image_url'); // Hapus URL jika beralih ke unggahan file
        }
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEditing) {
                $project = Project::findOrFail($this->projectId);
            } else {
                $project = new Project();
            }

            // Reset field gambar yang tidak digunakan
            if ($this->useExternalImage) {
                // Hapus gambar lokal jika ada
                if ($project->image && Storage::disk('public')->exists($project->image)) {
                    Storage::disk('public')->delete($project->image);
                }
                $project->image = null;
                $project->external_image_url = $this->external_image_url;
            } else {
                $project->external_image_url = null;
                // Handle image upload
                if ($this->image) {
                    if ($this->isEditing && $project->image) {
                        Storage::disk('public')->delete($project->image);
                    }
                    $imagePath = $this->image->store('projects', 'public');
                    $project->image = $imagePath;
                } elseif ($this->isEditing && !$this->existing_image) {
                    // Hapus gambar lokal jika user menghapusnya
                    if ($project->image) {
                        Storage::disk('public')->delete($project->image);
                    }
                    $project->image = null;
                }
            }

            // Save project data
            $project->fill([
                'title' => $this->title,
                'description' => $this->description,
                'category' => $this->category ?: null,
                'client' => $this->client ?: null,
                'location' => $this->location ?: null,
                'year' => $this->year,
                'status' => $this->status,
                'featured' => $this->featured,
                'sort_order' => $this->sort_order,
            ]);

            $project->save();

            session()->flash('message', $this->isEditing ? 'Project updated successfully!' : 'Project created successfully!');

            return redirect()->route('content-admin.projects');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while saving the project: ' . $e->getMessage());
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
                $this->image = null; // Clear temporary upload as well
                session()->flash('message', 'Image removed successfully!');
            }
        }
    }

    public function render()
    {
        return view('livewire.project.form');
    }
}
