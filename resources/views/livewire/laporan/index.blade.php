<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        <div>
            <flux:heading size="xl" level="1">Laporan Penjualan</flux:heading>
            <flux:text class="mt-1">Ringkasan omset, HPP, dan laba kotor per periode</flux:text>
        </div>

        {{-- Period Filter --}}
        <div class="flex flex-wrap items-end gap-3 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:field>
                <flux:label>Dari Tanggal</flux:label>
                <flux:input type="date" wire:model.live="dari" />
            </flux:field>
            <flux:field>
                <flux:label>Sampai Tanggal</flux:label>
                <flux:input type="date" wire:model.live="sampai" />
            </flux:field>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs text-zinc-500">Total Pesanan</p>
                <p class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalPesanan }}</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs text-zinc-500">Total Omset</p>
                <p class="mt-1 text-xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($totalOmset, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs text-zinc-500">Total HPP</p>
                <p class="mt-1 text-xl font-bold text-red-500 dark:text-red-400">Rp {{ number_format($totalHpp, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs text-zinc-500">Laba Kotor</p>
                <p class="mt-1 text-xl font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($totalProfit, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">No. Pesanan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Tanggal</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Omset</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">HPP</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Laba</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Margin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($orders as $order)
                        <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-3 font-mono text-sm font-medium text-zinc-900 dark:text-white">#{{ $order->order_number }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-right text-sm text-zinc-700 dark:text-zinc-300">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-sm text-red-500">Rp {{ number_format($order->total_hpp, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-blue-600 dark:text-blue-400">Rp {{ number_format($order->gross_profit, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-sm text-zinc-500">{{ $order->gross_margin_percent }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-sm text-zinc-400">Tidak ada data pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

</div>
