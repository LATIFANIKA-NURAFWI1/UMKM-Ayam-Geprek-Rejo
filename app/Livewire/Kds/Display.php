<?php

namespace App\Livewire\Kds;

use App\Models\Order;
use App\Services\OrderService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.kds')]
#[Title('KDS - Dapur Geprek Rejo')]
class Display extends Component
{
    public ?int $completingOrderId = null;

    // ─── Computed Properties ─────────────────────────────────────────────────

    #[Computed]
    public function kitchenOrders()
    {
        return Order::with(['details'])
            ->whereIn('status', ['confirmed', 'preparing'])
            ->today()
            ->orderBy('confirmed_at')
            ->get();
    }

    // ─── Actions ─────────────────────────────────────────────────────────────

    public function startPreparing(int $orderId): void
    {
        app(OrderService::class)->startPreparing($orderId);
        unset($this->kitchenOrders);
    }

    public function completeOrder(int $orderId): void
    {
        app(OrderService::class)->completeOrder($orderId);
        unset($this->kitchenOrders);
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.kds.display');
    }
}
