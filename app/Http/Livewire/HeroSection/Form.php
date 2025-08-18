<?php

namespace App\Http\Livewire\HeroSection;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\HeroSection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Form extends Component
{
    use WithFileUploads;

    public $heroId;
    public $title, $subtitle, $description, $button_text, $background_image, $background_video, $background_type;
    public $isEdit = false;

    public $existing_background_image_path;
    public $existing_background_video_path;

    protected function rules()
    {
        return [
            'title'           => 'required|string|max:255',
            'subtitle'        => 'required|string|max:255',
            'description'     => 'required|string',
            'button_text'     => 'required|string|max:100',
            'background_type' => 'required|in:image,video',
            'background_image' => [
                'nullable',
                Rule::requiredIf($this->background_type === 'image' && !$this->existing_background_image_path),
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:5120',
            ],
            'background_video' => [
                'nullable',
                Rule::requiredIf($this->background_type === 'video' && !$this->existing_background_video_path),
                'mimes:mp4,avi,mov,wmv,webm',
                'max:51200',
            ],
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->heroId = $id;
            $this->isEdit = true;
            $this->loadHeroSection();
        } else {
            $this->background_type = 'image';
        }
    }

    public function loadHeroSection()
    {
        $hero = HeroSection::findOrFail($this->heroId);
        $this->title       = $hero->title;
        $this->subtitle    = $hero->subtitle;
        $this->description = $hero->description;
        $this->button_text = $hero->button_text;
        $this->background_type = $hero->background_type ?? 'image';
        $this->existing_background_image_path = $hero->background_image;
        $this->existing_background_video_path = $hero->background_video;
    }

    public function updatedBackgroundType()
    {
        $this->background_image = null;
        $this->background_video = null;
    }

    public function save()
    {
        $this->validate($this->rules());

        $data = [
            'title'           => $this->title,
            'subtitle'        => $this->subtitle,
            'description'     => $this->description,
            'button_text'     => $this->button_text,
            'button_url'      => '#product-preview', // default karena input URL ditiadakan
            'background_type' => $this->background_type,
        ];

        if ($this->isEdit) {
            $hero = HeroSection::findOrFail($this->heroId);

            if ($this->background_type === 'image' && $this->background_image) {
                if ($hero->background_image) Storage::disk('public')->delete($hero->background_image);
                if ($hero->background_video) Storage::disk('public')->delete($hero->background_video);
                $data['background_image'] = $this->background_image->store('hero-sections/images', 'public');
                $data['background_video'] = null;
            } elseif ($this->background_type === 'video' && $this->background_video) {
                if ($hero->background_image) Storage::disk('public')->delete($hero->background_image);
                if ($hero->background_video) Storage::disk('public')->delete($hero->background_video);
                $data['background_video'] = $this->background_video->store('hero-sections/videos', 'public');
                $data['background_image'] = null;
            } else {
                // Pastikan field yang tidak dipilih null
                if ($this->background_type === 'image') {
                    $data['background_video'] = null;
                } else {
                    $data['background_image'] = null;
                }
            }

            $hero->update($data);
            // pakai flash + iziToast di index
            session()->flash('success', 'Hero section berhasil diperbarui.');
        } else {
            $data['is_active'] = true;

            if ($this->background_type === 'image' && $this->background_image) {
                $data['background_image'] = $this->background_image->store('hero-sections/images', 'public');
                $data['background_video'] = null;
            } elseif ($this->background_type === 'video' && $this->background_video) {
                $data['background_video'] = $this->background_video->store('hero-sections/videos', 'public');
                $data['background_image'] = null;
            }

            HeroSection::create($data);
            session()->flash('success', 'Hero section berhasil dibuat.');
        }

        return redirect()->route('content-admin.hero-sections');
    }

    public function render()
    {
        return view('livewire.hero-section.form')->layout('layouts.app');
    }
}
