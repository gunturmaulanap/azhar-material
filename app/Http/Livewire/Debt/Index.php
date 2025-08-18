<?php

namespace App\Http\Livewire\Debt;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search, $startDate, $endDate;
    public $perPage = 10;

    // simpan id yg mau dihapus
    public $deleteId;

    protected $listeners = [
        'confirm' => 'delete',   // dipanggil dari alert.blade (Livewire.emit('confirm', id))
        'perpage' => 'setPerPage',
    ];

    public function setPerPage($value)
    {
        $this->perPage = (int) $value ?: 10;
        $this->resetPage();
    }

    /** Tampilkan dialog konfirmasi (sinkron dg layouts/js/alert.blade.php -> toast:confirm) */
    public function validationDelete($id)
    {
        $this->deleteId = $id;

        $this->dispatchBrowserEvent('toast:confirm', [
            'id'      => $id,
            'title'   => 'Hapus data hutang?',
            'message' => 'Transaksi hutang akan dihapus. Lanjutkan?',
        ]);
    }

    /** Eksekusi hapus setelah user klik YA di dialog konfirmasi */
    public function delete($id = null)
    {
        $id = $id ?? $this->deleteId;

        $trx = Transaction::find($id);
        if (!$trx) {
            $this->dispatchBrowserEvent('toast:error', ['message' => 'Data tidak ditemukan!']);
            return;
        }

        $deleted = $trx->delete();

        if ($deleted) {
            $this->dispatchBrowserEvent('toast:warning', ['message' => 'Data terhapus!']);
            $this->resetPage();
        } else {
            $this->dispatchBrowserEvent('toast:error', ['message' => 'Gagal menghapus data.']);
        }
    }

    // reset halaman saat filter berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingStartDate()
    {
        $this->resetPage();
    }
    public function updatingEndDate()
    {
        $this->resetPage();
    }
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = Transaction::where('status', 'hutang')
            ->when($this->search, function ($query) {
                return $query->whereHas('customer', function ($subquery) {
                    $subquery->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                return $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        return view('livewire.debt.index', compact('data'));
    }
}
