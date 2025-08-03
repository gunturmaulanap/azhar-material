<?php

namespace App\Http\Livewire\HeroSection;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\HeroSection;

class Form extends Component
{
    use WithFileUploads;

    public $heroId;
    public $title, $subtitle, $description, $button_text, $button_url, $background_image;
    public $isEdit = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'subtitle' => 'required|string|max:255',
        'description' => 'required|string',
        'button_text' => 'required|string|max:100',
        'button_url' => 'required|url|max:255',
        'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->heroId = $id;
            $this->isEdit = true;
            $this->loadHeroSection();
        }
    }

    public function loadHeroSection()
    {
        $hero = HeroSection::findOrFail($this->heroId);
        $this->title = $hero->title;
        $this->subtitle = $hero->subtitle;
        $this->description = $hero->description;
        $this->button_text = $hero->button_text;
        $this->button_url = $hero->button_url;
    }

    public function save()
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
            if ($this->isEdit) {
                $hero = HeroSection::findOrFail($this->heroId);
                if ($hero->background_image) {
                    \Storage::disk('public')->delete($hero->background_image);
                }
            }
            $data['background_image'] = $this->background_image->store('hero-sections', 'public');
        }

        if ($this->isEdit) {
            $hero = HeroSection::findOrFail($this->heroId);
            $hero->update($data);
            session()->flash('success', 'Hero section berhasil diperbarui.');
        } else {
            HeroSection::create($data);
            session()->flash('success', 'Hero section berhasil dibuat.');
        }

        return redirect()->route('content.hero-sections');
    }

    public function render()
    {
        return view('livewire.hero-section.form')
            ->layout('layouts.app');
    }
}