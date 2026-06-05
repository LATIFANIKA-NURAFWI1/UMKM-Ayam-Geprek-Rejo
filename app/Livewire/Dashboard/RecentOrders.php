<?php

namespace App\Livewire\Dashboard;

use App\Models\Order;
use Livewire\Component;

class RecentOrders extends Component
{
    public function render()
    {
        $orders = Order::today()
            ->with(['details.menuItem'])
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.dashboard.recent-orders', compact('orders'));
    }
}
