<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <flux:heading size="xl" level="1">Pesanan</flux:heading>
            <flux:text class="mt-1">Kelola dan pantau semua pesanan masuk</flux:text>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3">
        <flux:input type="date" wire:model.live="tanggal" class="w-full sm:w-44" />
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari no. pesanan…" icon="magnifying-glass" class="w-full sm:w-56" />
        <select wire:model.live="status"
            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700 shadow-sm focus:border-orange-400 focus:outline-none focus:ring-1 focus:ring-orange-400 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 sm:w-44">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Dikonfirmasi</option>
            <option value="preparing">Dimasak</option>
            <option value="completed">Selesai</option>
            <option value="cancelled">Dibatal</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">No. Pesanan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Meja / Tipe</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Item</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Total</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Waktu</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($orders as $order)
                    @php
                        $badge = match($order->status) {
                            'pending'   => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400',
                            'confirmed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                            'preparing' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-400',
                            'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400',
                            default     => 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400',
                        };
                        $label = match($order->status) {
                            'pending'   => 'Pending',
                            'confirmed' => 'Dikonfirmasi',
                            'preparing' => 'Dimasak',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatal',
                            default     => $order->status,
                        };
                    @endphp
                    <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                        <td class="px-4 py-3">
                            <span class="font-mono text-sm font-semibold text-zinc-900 dark:text-white">#{{ $order->order_number }}</span>
                            @if($order->queue_number)
                                <span class="ml-1 text-xs text-zinc-400">Antrian {{ $order->queue_number }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $order->table_number ? 'Meja '.$order->table_number : '-' }}
                            <span class="ml-1 rounded bg-zinc-100 px-1.5 py-0.5 text-xs dark:bg-zinc-700">
                                {{ $order->type === 'dine_in' ? 'Dine In' : 'Take Away' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $order->details->count() }} item</td>
                        <td class="px-4 py-3 text-right text-sm font-semibold text-zinc-900 dark:text-white">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badge }}">{{ $label }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-400">{{ $order->created_at->format('H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-1.5">
                                @if($order->status === 'pending')
                                    <flux:button size="sm" wire:click="konfirmasi({{ $order->id }})">Konfirmasi</flux:button>
                                @elseif(in_array($order->status, ['confirmed', 'preparing']))
                                    <flux:button size="sm" wire:click="selesai({{ $order->id }})">Selesai</flux:button>
                                @endif
                                @if(!in_array($order->status, ['completed', 'cancelled']))
                                    <flux:button size="sm" variant="danger"
                                        wire:click="batal({{ $order->id }})"
                                        wire:confirm="Batalkan pesanan ini?">Batal</flux:button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-sm text-zinc-400">
                            Tidak ada pesanan pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $orders->links() }}</div>

</div>
