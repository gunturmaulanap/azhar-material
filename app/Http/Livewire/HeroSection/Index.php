<?php

namespace App\Http\Livewire\HeroSection;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\HeroSection;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $title, $subtitle, $description, $button_text, $button_url, $background_image, $background_video, $background_type;
    public $isEdit = false;
    public $heroId;

    /** Biar dialog konfirmasi jalan: Livewire.emit('confirm', id) -> delete($id) */
    protected $listeners = ['confirm' => 'delete'];

    protected $rules = [
        'title'             => 'required|string|max:255',
        'subtitle'          => 'required|string|max:255',
        'description'       => 'required|string',
        'button_text'       => 'required|string|max:100',
        'button_url'        => 'required|url|max:255',
        'background_type'   => 'required|in:image,video',
        'background_image'  => 'nullable|required_if:background_type,image|image|mimes:jpeg,png,jpg,gif|max:5120',
        'background_video'  => 'nullable|required_if:background_type,video|mimes:mp4,avi,mov,wmv,webm|max:51200',
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
        $this->background_type = 'image';
    }

    public function store()
    {
        $this->validate();

        $data = [
            'title'           => $this->title,
            'subtitle'        => $this->subtitle,
            'description'     => $this->description,
            'button_text'     => $this->button_text,
            'button_url'      => $this->button_url,
            'background_type' => $this->background_type,
            'is_active'       => true,
        ];

        if ($this->background_type === 'image' && $this->background_image) {
            $data['background_image'] = $this->background_image->store('hero-sections/images', 'public');
        } elseif ($this->background_type === 'video' && $this->background_video) {
            $data['background_video'] = $this->background_video->store('hero-sections/videos', 'public');
        }

        HeroSection::create($data);

        $this->dispatchBrowserEvent('toast:success', ['message' => 'Hero section berhasil dibuat.']);
        $this->resetForm();
    }

    public function edit($id)
    {
        $hero = HeroSection::findOrFail($id);
        $this->heroId         = $hero->id;
        $this->title          = $hero->title;
        $this->subtitle       = $hero->subtitle;
        $this->description    = $hero->description;
        $this->button_text    = $hero->button_text;
        $this->button_url     = $hero->button_url;
        $this->background_type = $hero->background_type ?? 'image';
        $this->isEdit         = true;
    }

    public function update()
    {
        $this->validate();

        $hero = HeroSection::findOrFail($this->heroId);

        $data = [
            'title'           => $this->title,
            'subtitle'        => $this->subtitle,
            'description'     => $this->description,
            'button_text'     => $this->button_text,
            'button_url'      => $this->button_url,
            'background_type' => $this->background_type,
        ];

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
        }

        $hero->update($data);

        $this->dispatchBrowserEvent('toast:success', ['message' => 'Hero section berhasil diperbarui.']);
        $this->resetForm();
    }

    /** Tampilkan dialog konfirmasi iziToast */
    public function validationDelete($id)
    {
        $this->dispatchBrowserEvent('toast:confirm', [
            'id'      => $id,
            'title'   => 'Hapus hero section?',
            'message' => 'Data akan dihapus permanen. Lanjutkan?',
        ]);
    }

    /** Dijalankan setelah user klik YA pada dialog (Livewire.emit("confirm", id)) */
    public function delete($id)
    {
        $hero = HeroSection::find($id);

        if (!$hero) {
            $this->dispatchBrowserEvent('toast:error', ['message' => 'Data tidak ditemukan.']);
            return;
        }

        if ($hero->background_image) Storage::disk('public')->delete($hero->background_image);
        if ($hero->background_video) Storage::disk('public')->delete($hero->background_video);

        $hero->delete();

        $this->dispatchBrowserEvent('toast:warning', ['message' => 'Hero section terhapus!']);
    }

    public function resetForm()
    {
        $this->title = '';
        $this->subtitle = '';
        $this->description = '';
        $this->button_text = '';
        $this->button_url = '';
        $this->background_image = null;
        $this->background_video = null;
        $this->background_type = 'image';
        $this->heroId = null;
        $this->isEdit = false;
    }
}
