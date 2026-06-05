<?php

namespace App\Livewire\Laporan;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public string $dari  = '';
    public string $sampai = '';

    public function mount(): void
    {
        $this->dari   = today()->startOfMonth()->toDateString();
        $this->sampai = today()->toDateString();
    }

    public function render()
    {
        $orders = Order::paid()
            ->forPeriod($this->dari, $this->sampai)
            ->with('details')
            ->latest()
            ->get();

        $totalOmset   = $orders->sum('total_amount');
        $totalHpp     = $orders->sum('total_hpp');
        $totalProfit  = $orders->sum('gross_profit');
        $totalPesanan = $orders->count();

        return view('livewire.laporan.index', compact(
            'orders', 'totalOmset', 'totalHpp', 'totalProfit', 'totalPesanan'
        ));
    }
}
