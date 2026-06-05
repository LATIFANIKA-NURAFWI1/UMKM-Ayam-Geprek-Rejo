<?php

namespace App\Livewire\Dashboard;

use App\Models\MenuItem;
use App\Models\Order;
use Livewire\Component;

class StatsCards extends Component
{
    public int $totalPesananHariIni    = 0;
    public string $omsetHariIni        = '0';
    public int $pesananPending         = 0;
    public int $menuAktif              = 0;

    public function mount(): void
    {
        $this->totalPesananHariIni = Order::today()->count();

        $this->omsetHariIni = number_format(
            Order::today()->paid()->sum('total_amount'),
            0, ',', '.'
        );

        $this->pesananPending = Order::pending()->today()->count();

        $this->menuAktif = MenuItem::available()->count();
    }

    public function render()
    {
        return view('livewire.dashboard.stats-cards');
    }
}
