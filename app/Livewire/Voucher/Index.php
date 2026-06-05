<?php

namespace App\Livewire\Voucher;

use App\Models\Voucher;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public function updatedSearch(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        Voucher::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Voucher berhasil dihapus.');
    }

    public function render()
    {
        $vouchers = Voucher::when($this->search, fn ($q) => $q
                ->where('code', 'like', "%{$this->search}%")
                ->orWhere('name', 'like', "%{$this->search}%")
            )
            ->latest()
            ->paginate(15);

        return view('livewire.voucher.index', compact('vouchers'));
    }
}
