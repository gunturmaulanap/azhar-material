<?php

namespace App\Http\Livewire\About;

use App\Models\About;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $aboutId;
    public $title;
    public $description;
    public $image;
    public $company_name;
    public $company_address;
    public $company_phone;
    public $company_email;
    public $company_website;
    public $vision;
    public $mission;
    public $is_active = true;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|max:1024',
        'company_name' => 'required|string|max:255',
        'company_address' => 'nullable|string|max:255',
        'company_phone' => 'nullable|string|max:255',
        'company_email' => 'nullable|email|max:255',
        'company_website' => 'nullable|url|max:255',
        'vision' => 'nullable|string',
        'mission' => 'nullable|string',
        'is_active' => 'boolean'
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->aboutId = $id;
            $this->loadAbout();
        }
    }

    public function loadAbout()
    {
        $about = About::findOrFail($this->aboutId);
        $this->title = $about->title;
        $this->description = $about->description;
        $this->company_name = $about->company_name;
        $this->company_address = $about->company_address;
        $this->company_phone = $about->company_phone;
        $this->company_email = $about->company_email;
        $this->company_website = $about->company_website;
        $this->vision = $about->vision;
        $this->mission = $about->mission;
        $this->is_active = $about->is_active;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'company_name' => $this->company_name,
            'company_address' => $this->company_address,
            'company_phone' => $this->company_phone,
            'company_email' => $this->company_email,
            'company_website' => $this->company_website,
            'vision' => $this->vision,
            'mission' => $this->mission,
            'is_active' => $this->is_active,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('about', 'public');
        }

        if ($this->aboutId) {
            $about = About::findOrFail($this->aboutId);
            $about->update($data);
            session()->flash('message', 'Data about berhasil diperbarui.');
        } else {
            About::create($data);
            session()->flash('message', 'Data about berhasil ditambahkan.');
        }

        return redirect()->route('content-admin.about');
    }

    public function render()
    {
        return view('livewire.about.form');
    }
}
