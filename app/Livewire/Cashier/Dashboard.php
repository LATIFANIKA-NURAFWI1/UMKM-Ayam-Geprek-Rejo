<?php

namespace App\Livewire\Cashier;

use App\Exceptions\InsufficientStockException;
use App\Models\Order;
use App\Services\OrderService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.cashier')]
#[Title('Dashboard Kasir')]
class Dashboard extends Component
{
    // ─── Detail Modal ────────────────────────────────────────────────────────

    public ?int $selectedOrderId = null;

    public bool $showDetailModal = false;

    // ─── Cancel Modal ────────────────────────────────────────────────────────

    public bool $showCancelModal = false;

    public ?int $cancelOrderId = null;

    #[Validate('required', message: 'Alasan pembatalan wajib diisi.')]
    #[Validate('min:3', message: 'Alasan minimal 3 karakter.')]
    public string $cancelReason = '';

    // ─── Confirm Payment Modal ───────────────────────────────────────────────

    public ?int $confirmingOrderId = null;

    public string $confirmPaymentMethod = 'qris';

    // ─── Computed Properties ─────────────────────────────────────────────────

    #[Computed]
    public function pendingOrders()
    {
        return Order::with(['details', 'member'])
            ->pending()
            ->today()
            ->latest()
            ->get();
    }

    #[Computed]
    public function confirmedOrders()
    {
        return Order::with(['details', 'member'])
            ->confirmed()
            ->today()
            ->latest()
            ->get();
    }

    #[Computed]
    public function selectedOrder()
    {
        if ($this->selectedOrderId === null) {
            return null;
        }

        return Order::with(['details.menuItem', 'member'])
            ->find($this->selectedOrderId);
    }

    // ─── Detail Modal Actions ─────────────────────────────────────────────────

    public function openDetailModal(int $orderId): void
    {
        $this->selectedOrderId = $orderId;
        $this->showDetailModal = true;
        unset($this->selectedOrder);
    }

    public function closeDetailModal(): void
    {
        $this->selectedOrderId = null;
        $this->showDetailModal = false;
        unset($this->selectedOrder);
    }

    // ─── Confirm Payment Modal Actions ────────────────────────────────────────

    public function openConfirmModal(int $orderId): void
    {
        $order = Order::find($orderId);
        $this->confirmingOrderId    = $orderId;
        $this->confirmPaymentMethod = $order ? $order->payment_method : 'qris';
        $this->showDetailModal      = false; // tutup detail modal jika sedang buka
    }

    public function confirmPayment(): void
    {
        try {
            app(OrderService::class)->confirmPayment(
                $this->confirmingOrderId,
                $this->confirmPaymentMethod,
                auth()->id()
            );

            session()->flash('status', 'Pembayaran dikonfirmasi! Pesanan diteruskan ke dapur.');
        } catch (InsufficientStockException $e) {
            session()->flash('error', 'Stok bahan baku tidak mencukupi!');
        } catch (\RuntimeException $e) {
            session()->flash('error', $e->getMessage());
        }

        // Reset state & invalidate computed cache
        $this->confirmingOrderId    = null;
        $this->confirmPaymentMethod = 'qris';

        unset($this->pendingOrders);
        unset($this->confirmedOrders);
    }

    // ─── Cancel Modal Actions ─────────────────────────────────────────────────

    public function openCancelModal(int $orderId): void
    {
        $this->cancelOrderId  = $orderId;
        $this->showCancelModal = true;
    }

    public function cancelOrder(): void
    {
        $this->validateOnly('cancelReason');

        try {
            app(OrderService::class)->cancelOrder($this->cancelOrderId, $this->cancelReason);
            session()->flash('status', 'Pesanan dibatalkan.');
        } catch (\RuntimeException $e) {
            session()->flash('error', $e->getMessage());
        }

        // Reset state & invalidate computed cache
        $this->cancelOrderId  = null;
        $this->cancelReason   = '';
        $this->showCancelModal = false;

        unset($this->pendingOrders);
    }

    public function closeCancelModal(): void
    {
        $this->cancelOrderId  = null;
        $this->cancelReason   = '';
        $this->showCancelModal = false;
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.cashier.dashboard');
    }
}
