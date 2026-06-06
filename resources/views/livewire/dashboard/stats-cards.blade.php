@php $s = $this->stats; @endphp

<div class="grid grid-cols-2 gap-4 lg:grid-cols-4 xl:grid-cols-7">

    {{-- Total Pesanan --}}
    <div class="col-span-1 flex flex-col gap-3 rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-2xl dark:bg-blue-900/40">🛒</div>
        <div>
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Pesanan</p>
            <p class="text-3xl font-black text-zinc-900 dark:text-white">{{ $s['total_pesanan'] }}</p>
            <p class="text-xs text-zinc-400">{{ $s['paid_count'] }} terbayar</p>
        </div>
    </div>

    {{-- Omset Hari Ini --}}
    <div class="col-span-1 flex flex-col gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm dark:border-emerald-800/40 dark:bg-emerald-900/20">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-2xl">💰</div>
        <div>
            <p class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Omset Hari Ini</p>
            <p class="text-xl font-black text-emerald-900 dark:text-emerald-300">Rp {{ number_format($s['omset'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Laba Kotor --}}
    <div class="col-span-1 flex flex-col gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-5 shadow-sm dark:border-blue-800/40 dark:bg-blue-900/20">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-2xl">📈</div>
        <div>
            <p class="text-xs font-medium text-blue-700 dark:text-blue-400">Laba Kotor</p>
            <p class="text-xl font-black text-blue-900 dark:text-blue-300">Rp {{ number_format($s['gross_profit'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Pending --}}
    <div @class([
        'col-span-1 flex flex-col gap-3 rounded-2xl border p-5 shadow-sm',
        'border-amber-300 bg-amber-50 dark:border-amber-800/40 dark:bg-amber-900/20' => $s['pending'] > 0,
        'border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900' => $s['pending'] === 0,
    ])>
        <div @class([
            'flex h-11 w-11 items-center justify-center rounded-xl text-2xl',
            'bg-amber-100 dark:bg-amber-900/40' => $s['pending'] > 0,
            'bg-zinc-100 dark:bg-zinc-800' => $s['pending'] === 0,
        ])>⏳</div>
        <div>
            <p @class(['text-xs font-medium', 'text-amber-700 dark:text-amber-400' => $s['pending'] > 0, 'text-zinc-500 dark:text-zinc-400' => $s['pending'] === 0])>
                Menunggu Konfirmasi
            </p>
            <p @class(['text-3xl font-black', 'text-amber-900 dark:text-amber-300' => $s['pending'] > 0, 'text-zinc-900 dark:text-white' => $s['pending'] === 0])>
                {{ $s['pending'] }}
            </p>
            @if($s['pending'] > 0)
                <a href="{{ route('cashier.dashboard') }}" wire:navigate class="mt-1 inline-block text-xs font-medium text-amber-700 underline hover:text-amber-900">
                    → Ke Kasir
                </a>
            @endif
        </div>
    </div>

    {{-- Menu Aktif --}}
    <div class="col-span-1 flex flex-col gap-3 rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 text-2xl dark:bg-orange-900/40">🍗</div>
        <div>
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Menu Aktif</p>
            <p class="text-3xl font-black text-zinc-900 dark:text-white">{{ $s['menu_aktif'] }}</p>
        </div>
    </div>

    {{-- Stok Kritis --}}
    <div @class([
        'col-span-1 flex flex-col gap-3 rounded-2xl border p-5 shadow-sm lg:col-span-2',
        'border-red-300 bg-red-50 dark:border-red-800/40 dark:bg-red-900/20' => $s['stok_kritis'] > 0,
        'border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900' => $s['stok_kritis'] === 0,
    ])>
        <div @class([
            'flex h-11 w-11 items-center justify-center rounded-xl text-2xl',
            'bg-red-100 dark:bg-red-900/40' => $s['stok_kritis'] > 0,
            'bg-zinc-100 dark:bg-zinc-800' => $s['stok_kritis'] === 0,
        ])>{{ $s['stok_kritis'] > 0 ? '⚠️' : '📦' }}</div>
        <div>
            <p @class(['text-xs font-medium', 'text-red-700 dark:text-red-400' => $s['stok_kritis'] > 0, 'text-zinc-500 dark:text-zinc-400' => $s['stok_kritis'] === 0])>
                Bahan Stok Rendah
            </p>
            <p @class(['text-3xl font-black', 'text-red-900 dark:text-red-300' => $s['stok_kritis'] > 0, 'text-zinc-900 dark:text-white' => $s['stok_kritis'] === 0])>
                {{ $s['stok_kritis'] }}
            </p>
            @if($s['stok_kritis'] > 0)
                <a href="{{ route('stok.index') }}" wire:navigate class="mt-1 inline-block text-xs font-medium text-red-700 underline hover:text-red-900">
                    → Cek Stok
                </a>
            @else
                <p class="mt-1 text-xs text-zinc-400">Semua aman ✅</p>
            @endif
        </div>
    </div>

</div>
