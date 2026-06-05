<div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

    {{-- Total Pesanan --}}
    <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/40">
                <flux:icon name="shopping-cart" class="h-5 w-5 text-blue-600 dark:text-blue-400" />
            </div>
            <div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Pesanan Hari Ini</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalPesananHariIni }}</p>
            </div>
        </div>
    </div>

    {{-- Omset Hari Ini --}}
    <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/40">
                <flux:icon name="banknotes" class="h-5 w-5 text-green-600 dark:text-green-400" />
            </div>
            <div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Omset Hari Ini</p>
                <p class="text-xl font-bold text-zinc-900 dark:text-white">Rp {{ $omsetHariIni }}</p>
            </div>
        </div>
    </div>

    {{-- Pesanan Pending --}}
    <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-100 dark:bg-yellow-900/40">
                <flux:icon name="clock" class="h-5 w-5 text-yellow-600 dark:text-yellow-400" />
            </div>
            <div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Pending</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $pesananPending }}</p>
            </div>
        </div>
    </div>

    {{-- Menu Aktif --}}
    <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/40">
                <flux:icon name="fire" class="h-5 w-5 text-orange-600 dark:text-orange-400" />
            </div>
            <div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Menu Aktif</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $menuAktif }}</p>
            </div>
        </div>
    </div>

</div>
