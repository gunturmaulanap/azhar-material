<?php

namespace App\Http\Livewire\HeroSection;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HeroSection;

class Index extends Component
{
    use WithPagination;

    public $title, $subtitle, $description, $button_text, $button_url, $background_image;
    public $isEdit = false;
    public $heroId;

    protected $rules = [
        'title' => 'required|string|max:255',
        'subtitle' => 'required|string|max:255',
        'description' => 'required|string',
        'button_text' => 'required|string|max:100',
        'button_url' => 'required|url|max:255',
        'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ];

    public function render()
    {
        $heroSections = HeroSection::orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.hero-section.index', compact('heroSections'));
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
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'button_text' => $this->button_text,
            'button_url' => $this->button_url,
        ];

        if ($this->background_image) {
            $data['background_image'] = $this->background_image->store('hero-sections', 'public');
        }

        HeroSection::create($data);

        session()->flash('message', 'Hero section created successfully.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $hero = HeroSection::findOrFail($id);
        $this->heroId = $hero->id;
        $this->title = $hero->title;
        $this->subtitle = $hero->subtitle;
        $this->description = $hero->description;
        $this->button_text = $hero->button_text;
        $this->button_url = $hero->button_url;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        $hero = HeroSection::findOrFail($this->heroId);
        
        $data = [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'button_text' => $this->button_text,
            'button_url' => $this->button_url,
        ];

        if ($this->background_image) {
            // Delete old image
            if ($hero->background_image) {
                \Storage::disk('public')->delete($hero->background_image);
            }
            $data['background_image'] = $this->background_image->store('hero-sections', 'public');
        }

        $hero->update($data);

        session()->flash('message', 'Hero section updated successfully.');
        $this->resetForm();
    }

    public function delete($id)
    {
        $hero = HeroSection::findOrFail($id);
        
        if ($hero->background_image) {
            \Storage::disk('public')->delete($hero->background_image);
        }
        
        $hero->delete();
        session()->flash('message', 'Hero section deleted successfully.');
    }

    public function resetForm()
    {
        $this->title = '';
        $this->subtitle = '';
        $this->description = '';
        $this->button_text = '';
        $this->button_url = '';
        $this->background_image = null;
        $this->heroId = null;
        $this->isEdit = false;
    }
} 