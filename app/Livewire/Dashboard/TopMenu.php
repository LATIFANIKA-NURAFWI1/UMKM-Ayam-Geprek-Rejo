<?php

namespace App\Livewire\Dashboard;

use App\Models\OrderDetail;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TopMenu extends Component
{
    public function render()
    {
        $topMenus = OrderDetail::select('menu_item_id', 'menu_item_name', DB::raw('SUM(quantity) as total_terjual'))
            ->whereHas('order', fn ($q) => $q->today())
            ->whereNotNull('menu_item_id')
            ->groupBy('menu_item_id', 'menu_item_name')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        return view('livewire.dashboard.top-menu', compact('topMenus'));
    }
}
