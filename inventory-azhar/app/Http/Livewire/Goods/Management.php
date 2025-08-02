<?php

namespace App\Http\Livewire\Goods;

use App\Models\retur;
use Livewire\Component;

class Management extends Component
{
    public $perPage = 10;


    protected $listeners = [ 
        'confirm' => 'deleted', // Pastikan event 'confirm' memanggil fungsi 'deleted'
    ];
    
    public function validationDelete($id)
    {
        // Log event untuk memastikan fungsi dipanggil
        // \Log::info("Validation delete triggered for ID: {$id}");
    
        $this->dispatchBrowserEvent('validation', [
            'id' => $id
        ]);
    }
    

    public function deleted($id)
{
    // Ambil retur dan barang terkait
    $retur = Retur::findOrFail($id);

    // Dapatkan barang dari relasi
    $goods = $retur->goods;

    // Hapus retur
    $deleted = $retur->delete();

    if ($deleted) {
        foreach ($goods as $good) {
            // Increment stok barang (model Goods, bukan Retur)
            \App\Models\Goods::where('id', $good->id)->increment('stock', $good->pivot->qty);
        }

        // Kirim event ke browser
        $this->dispatchBrowserEvent('deleted');
    }
}

    public function render()
    {
        $data = retur::orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.goods.management', compact('data'));
    }
}
