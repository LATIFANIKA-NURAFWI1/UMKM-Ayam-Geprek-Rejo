<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <flux:heading size="xl" level="1">Stok Bahan Baku</flux:heading>
            <flux:text class="mt-1">Pantau ketersediaan bahan baku dapur</flux:text>
        </div>
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari bahan baku…" icon="magnifying-glass" class="w-full sm:w-64" />

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Nama Bahan</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Stok</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Satuan</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Harga/Unit</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($stocks as $stock)
                    @php $isLow = $stock->stock_qty <= ($stock->min_stock ?? 0); @endphp
                    <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50 {{ $isLow ? 'bg-red-50/50 dark:bg-red-900/10' : '' }}">
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $stock->name }}</td>
                        <td class="px-4 py-3 text-right text-sm font-semibold {{ $isLow ? 'text-red-600' : 'text-zinc-700 dark:text-zinc-300' }}">
                            {{ number_format($stock->stock_qty, 2) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $stock->unit }}</td>
                        <td class="px-4 py-3 text-right text-sm text-zinc-600 dark:text-zinc-400">
                            Rp {{ number_format($stock->unit_cost, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($isLow)
                                <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-600 dark:bg-red-900/40 dark:text-red-400">
                                    ⚠ Stok Rendah
                                </span>
                            @else
                                <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/40 dark:text-green-400">
                                    Aman
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-sm text-zinc-400">Belum ada data bahan baku.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $stocks->links() }}</div>

</div>
