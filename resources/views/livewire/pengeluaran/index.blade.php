<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <flux:heading size="xl" level="1">Pengeluaran</flux:heading>
            <flux:text class="mt-1">Catat dan pantau biaya operasional</flux:text>
        </div>
    </div>

    {{-- Summary --}}
    <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
        <p class="text-sm text-zinc-500">Total Pengeluaran Bulan Ini</p>
        <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">
            Rp {{ number_format($totalBulanIni, 0, ',', '.') }}
        </p>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3">
        <flux:input type="month" wire:model.live="bulan" class="w-full sm:w-44" />
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari deskripsi…" icon="magnifying-glass" class="w-full sm:w-64" />
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Deskripsi</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Jumlah</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($expenses as $expense)
                    <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                        <td class="px-4 py-3 text-sm text-zinc-500">
                            {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-300">{{ $expense->description }}</td>
                        <td class="px-4 py-3 text-right text-sm font-semibold text-red-600 dark:text-red-400">
                            Rp {{ number_format($expense->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <flux:button size="sm" variant="danger" icon="trash"
                                wire:click="delete({{ $expense->id }})"
                                wire:confirm="Hapus catatan pengeluaran ini?">Hapus</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-12 text-center text-sm text-zinc-400">Tidak ada pengeluaran pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $expenses->links() }}</div>

</div>
