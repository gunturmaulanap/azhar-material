<?php

namespace App\Http\Livewire\Team;

use App\Models\Team;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $teamId;
    public $name;
    public $position;
    public $description;
    public $image;
    public $email;
    public $phone;
    public $linkedin;
    public $twitter;
    public $instagram;
    public $is_active = true;
    public $order = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:1024',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:255',
        'linkedin' => 'nullable|url|max:255',
        'twitter' => 'nullable|url|max:255',
        'instagram' => 'nullable|url|max:255',
        'is_active' => 'boolean',
        'order' => 'integer|min:0'
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->teamId = $id;
            $this->loadTeam();
        }
    }

    public function loadTeam()
    {
        $team = Team::findOrFail($this->teamId);
        $this->name = $team->name;
        $this->position = $team->position;
        $this->description = $team->description;
        $this->email = $team->email;
        $this->phone = $team->phone;
        $this->linkedin = $team->linkedin;
        $this->twitter = $team->twitter;
        $this->instagram = $team->instagram;
        $this->is_active = $team->is_active;
        $this->order = $team->order;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'position' => $this->position,
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'linkedin' => $this->linkedin,
            'twitter' => $this->twitter,
            'instagram' => $this->instagram,
            'is_active' => $this->is_active,
            'order' => $this->order,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('teams', 'public');
        }

        if ($this->teamId) {
            $team = Team::findOrFail($this->teamId);
            $team->update($data);
            session()->flash('message', 'Data tim berhasil diperbarui.');
        } else {
            Team::create($data);
            session()->flash('message', 'Data tim berhasil ditambahkan.');
        }

        return redirect()->route('content.teams');
    }

    public function render()
    {
        return view('livewire.team.form');
    }
}
