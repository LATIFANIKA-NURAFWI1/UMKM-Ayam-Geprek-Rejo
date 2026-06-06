<?php

namespace App\Livewire\Pesanan;

use App\Models\Order;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $tanggal = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }
    public function updatedTanggal(): void { $this->resetPage(); }

    public function mount(): void
    {
        $this->tanggal = $this->tanggal ?: today()->toDateString();
    }

    public function konfirmasi(int $id): void
    {
        $order = Order::findOrFail($id);
        $order->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => auth()->id(),
        ]);
        $this->dispatch('notify', message: "Pesanan #{$order->order_number} dikonfirmasi.");
    }

    public function selesai(int $id): void
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'completed', 'completed_at' => now()]);
        $this->dispatch('notify', message: "Pesanan #{$order->order_number} selesai.");
    }

    public function batal(int $id): void
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'cancelled']);
        $this->dispatch('notify', message: "Pesanan #{$order->order_number} dibatalkan.");
    }

    public function render()
    {
        $orders = Order::with(['details.menuItem', 'member'])
            ->when($this->search, fn ($q) => $q->where('order_number', 'like', "%{$this->search}%"))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->tanggal, fn ($q) => $q->whereDate('created_at', $this->tanggal))
            ->latest()
            ->paginate(15);

        return view('livewire.pesanan.index', compact('orders'));
    }
}
