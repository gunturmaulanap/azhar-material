<?php

namespace App\Http\Livewire\Service;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $serviceId;
    public $name;
    public $description;
    public $icon;
    public $image;
    public $button_text;
    public $button_link;
    public $is_active = true;
    public $order = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'icon' => 'nullable|string|max:255',
        'image' => 'nullable|image|max:1024',
        'button_text' => 'nullable|string|max:255',
        'button_link' => 'nullable|url|max:255',
        'is_active' => 'boolean',
        'order' => 'integer|min:0'
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->serviceId = $id;
            $this->loadService();
        }
    }

    public function loadService()
    {
        $service = Service::findOrFail($this->serviceId);
        $this->name = $service->name;
        $this->description = $service->description;
        $this->icon = $service->icon;
        $this->button_text = $service->button_text;
        $this->button_link = $service->button_link;
        $this->is_active = $service->is_active;
        $this->order = $service->order;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'button_text' => $this->button_text,
            'button_link' => $this->button_link,
            'is_active' => $this->is_active,
            'order' => $this->order,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('services', 'public');
        }

        if ($this->serviceId) {
            $service = Service::findOrFail($this->serviceId);
            $service->update($data);
            session()->flash('message', 'Data layanan berhasil diperbarui.');
        } else {
            Service::create($data);
            session()->flash('message', 'Data layanan berhasil ditambahkan.');
        }

        return redirect()->route('content.services');
    }

    public function render()
    {
        return view('livewire.service.form');
    }
}
