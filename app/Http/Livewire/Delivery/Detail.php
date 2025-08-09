<?php

namespace App\Http\Livewire\Delivery;

use App\Models\ActDeliveryDetails;
use App\Models\ActDelivery;
use Livewire\Component;
use App\Models\Delivery;
use Illuminate\Support\Facades\DB;

use App\Models\DeliveryGoods;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\WithFileUploads;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan ini

class Detail extends Component
{
    use WithFileUploads;
    public $delivery, $deliveryGoods, $input;
    public bool $isUploading = false;

    public $actDeliveries = [];
    public $today;
    public $imagePreview;
    public $detail = [
        'images' => [],
        'image' => null, // Tambahkan ini untuk file tunggal
        'input' => 0, // Tambahkan ini agar tidak error saat pertama kali load
    ];
    public $detailInput = ['input' => 0];


    protected $table = 'act_deliveries_details';


    public function incrementInput()
    {
        // Default nilai awal
        $currentInput = $this->detail['input'] ?? 0;
        $newInput = $currentInput + 1;

        // Cek apakah index tersedia
        if (isset($this->detail['index']) && isset($this->deliveryGoods[$this->detail['index']])) {
            $maxQty = $this->deliveryGoods[$this->detail['index']]['qty'] - $this->detail['delivered'];

            if ($newInput > $maxQty) {
                $this->dispatchBrowserEvent('error', ['message' => 'Melebihi jumlah barang yang harus dikirim']);
                $this->detail['input'] = $maxQty;
            } elseif ($newInput <= 0) {
                $this->dispatchBrowserEvent('error', ['message' => 'Input barang tidak boleh kurang dari 1']);
                $this->detail['input'] = 1;
            } else {
                $this->detail['input'] = $newInput;
            }
        } else {
            // Fallback jika index tidak valid
            $this->dispatchBrowserEvent('error', ['message' => 'Data barang tidak ditemukan']);
        }
    }
    public function decrementInput()
    {
        $this->detail['input'] = max(0, ($this->detail['input'] ?? 0) - 1);
    }
    public function updatedDetailImage()
    {
        if ($this->detail['image']) {
            $this->imagePreview = $this->detail['image']->temporaryUrl();
        }
    }


    public function deleteImage($index)
    {
        unset($this->detail['images'][$index]);
        $this->detail['images'] = array_values($this->detail['images']); // Reset array index
    }

    // Method ini tidak lagi diperlukan karena kita akan menanganinya langsung di metode save()
    // public function setImage()
    // {
    //     if (!empty($this->detail['image'])) {
    //         $basePath = public_path('delivery/storage/images/products'); // Perbaikan utama di sini!

    //         // Cek jika folder belum ada, buat folder baru
    //         if (!file_exists($basePath)) {
    //             mkdir($basePath, 0755, true);
    //         }
    //     }
    // }


    public function setDetail($index)
    {
        $this->isUploading = true;
        $this->detail = [
            'index' => $index,
            'name' => $this->deliveryGoods[$index]['name'],
            'qty' => $this->deliveryGoods[$index]['qty'],
            'unit' => $this->deliveryGoods[$index]['unit'],
            'delivered' => $this->deliveryGoods[$index]['delivered'],
            'input' => $this->actDeliveries[$index]['qty'] ?? 0,
        ];
    }

    public function updatedDetailInput($value)
    {
        // Cek jika nilai input melebihi jumlah barang yang harus dikirim
        if ($value > ($this->deliveryGoods[$this->detail['index']]['qty'] - $this->detail['delivered'])) {
            $this->dispatchBrowserEvent('error', ['message' => 'Melebihi jumlah barang yang harus dikirim']);
            $this->detail['input'] = $this->deliveryGoods[$this->detail['index']]['qty'] - $this->detail['delivered'];
        }
        // Cek jika nilai input kurang dari 0
        elseif ($this->detail['input'] < 0) {
            $this->dispatchBrowserEvent('error', ['message' => 'Input barang tidak boleh kurang dari 0']);
            $this->detail['input'] = 0;
        }
        // Cek jika nilai input sama dengan 0
        elseif ($this->detail['input'] == 0) {
            $this->dispatchBrowserEvent('error', ['message' => 'Tambahkan barang yang ingin dikirim']);
        }
    }


    public function unsetDetail()
    {
        $this->detail = [];
    }

    public function resetInput()
    {
        $this->actDeliveries = [];
        $this->detail['images'] = []; // Reset images menjadi array kosong
    }


    public function submit()
    {
        // Pastikan detail['input'] tetap ada, dan menggunakan detailInput['input']
        $this->actDeliveries[$this->detail['index']] = $this->actDeliveries[$this->detail['index']] ?? [
            'user_id' => Auth::user()->id,
            'goods_id' => $this->deliveryGoods[$this->detail['index']]['goods_id'],
            'qty' => $this->detailInput['input'], // Gunakan detailInput['input']
            'name' => $this->deliveryGoods[$this->detail['index']]['name'],
            'unit' => $this->deliveryGoods[$this->detail['index']]['unit'],
            'created_at' => $this->today,
        ];

        // Pastikan detail['input'] digunakan
        if ($this->detail['input'] >= 1) {
            $this->actDeliveries[$this->detail['index']]['qty'] = $this->detail['input'];
        }

        // Pastikan detailInput['input'] digunakan juga
        if ($this->detailInput['input'] >= 1) {
            $this->actDeliveries[$this->detail['index']]['qty'] = $this->detailInput['input'];
        }

        $this->isUploading = true;
    }




    public function save()
    {
        if (empty($this->actDeliveries)) {
            $this->dispatchBrowserEvent('error', ['message' => 'Pilih barang yang sudah dikirim']);
            return;
        }

        $created_at = Carbon::now();

        // Proses penyimpanan ActDelivery
        foreach ($this->actDeliveries as $activity) {
            DB::transaction(function () use ($created_at, $activity) {
                ActDelivery::create([
                    'delivery_id' => $this->deliveryId,
                    'user_id' => $activity['user_id'],
                    'goods_id' => $activity['goods_id'],
                    'qty' => $activity['qty'],
                    'created_at' => $created_at,
                ]);
            });

            // Update delivered qty for deliveryGoods
            $deliveryGood = DeliveryGoods::where('delivery_id', $this->deliveryId)
                ->where('goods_id', $activity['goods_id'])
                ->first();

            if ($deliveryGood) {
                $deliveryGood->increment('delivered', $activity['qty']);
            }
        }

        // Proses penyimpanan ActDeliveryDetails dengan banyak gambar
        $images = [];
        if (isset($this->detail['images']) && is_array($this->detail['images'])) {
            foreach ($this->detail['images'] as $image) {
                // Perbaikan utama: Menggunakan Storage facade
                $uniqueFileName = $image->getFileName(); // Livewire sudah menghasilkan nama file unik di tmp

                // Simpan file ke folder `delivery/storage/images/products` di dalam `public`
                // Metode ini akan otomatis membuat direktori jika belum ada
                $path = $image->storeAs('delivery/storage/images/products', $uniqueFileName, 'public');
                $images[] = $path;
            }
        }

        DB::transaction(function () use ($created_at, $images) {
            ActDeliveryDetails::create([
                'delivery_id' => $this->deliveryId,
                'image' => $images, // Simpan array gambar
                'created_at' => $created_at,
            ]);
        });

        // Perbarui: Cek apakah `detail['images']` ada sebelum melakukan unset
        if (isset($this->detail['images'])) {
            $this->detail['images'] = [];
        }

        return redirect()->route(str_replace('_', '', auth()->user()->role) . '.delivery.detail', ['id' => $this->deliveryId])->with('success', 'Pengiriman barang berhasil disimpan!');
    }



    public $deliveryId;

    public function mount($id)
    {
        $this->today = Carbon::now();
        $this->deliveryId = $id;
        $this->delivery = Delivery::findOrFail($this->deliveryId);

        foreach ($this->delivery->goods as $good) {
            $this->deliveryGoods[] = [
                'goods_id' => $good->id,
                'name' => $good->name,
                'qty' => $good->pivot->qty,
                'unit' => $good->unit,
                'delivered' => $good->pivot->delivered,
            ];
        }
    }

    public function render()
    {
        $history = actDelivery::where('delivery_id', $this->deliveryId)
            ->orderBy('created_at', 'desc')
            ->get();
        $details = ActDeliveryDetails::where('delivery_id', $this->deliveryId)->get();

        // Perbaiki: Group by detik, bukan menit
        $groupedHistory = $history->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i:s'); // Format hingga detik
        });

        return view('livewire.delivery.detail', [
            'history' => $history,
            'groupedHistory' => $groupedHistory,
            'details' => $details,
        ]);
    }
}
