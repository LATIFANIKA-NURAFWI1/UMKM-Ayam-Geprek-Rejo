<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- ── Header ────────────────────────────────────────────── --}}
        <div>
            <flux:heading size="xl" level="1">Dashboard</flux:heading>
            <flux:text class="mt-1">Selamat datang di Sistem Self-Order Geprek Rejo 🍗</flux:text>
        </div>

        {{-- ── Stat Cards ─────────────────────────────────────────── --}}
        @livewire('dashboard.stats-cards')

        {{-- ── Recent Orders ────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Pesanan Terbaru --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center justify-between">
                    <flux:heading size="lg">Pesanan Hari Ini</flux:heading>
                    <flux:button href="{{ route('pesanan.index') }}" size="sm" variant="ghost" wire:navigate>
                        Lihat Semua →
                    </flux:button>
                </div>
                @livewire('dashboard.recent-orders')
            </div>

            {{-- Menu Terlaris --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center justify-between">
                    <flux:heading size="lg">Menu Terlaris</flux:heading>
                    <flux:button href="{{ route('menu.index') }}" size="sm" variant="ghost" wire:navigate>
                        Kelola Menu →
                    </flux:button>
                </div>
                @livewire('dashboard.top-menu')
            </div>

        </div>

    </div>
</x-layouts::app>
