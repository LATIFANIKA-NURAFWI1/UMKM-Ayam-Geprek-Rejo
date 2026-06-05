<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <flux:heading size="xl" level="1">Voucher</flux:heading>
                <flux:text class="mt-1">Kelola voucher diskon untuk pelanggan</flux:text>
            </div>
        </div>

        <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari kode atau nama voucher…" icon="magnifying-glass" class="w-full sm:w-72" />

        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Tipe</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Nilai</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500">Sisa Kuota</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Berlaku</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($vouchers as $voucher)
                        @php $expired = $voucher->expired_at && $voucher->expired_at->isPast(); @endphp
                        <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-3">
                                <span class="rounded bg-zinc-100 px-2 py-0.5 font-mono text-sm font-bold tracking-wider text-zinc-800 dark:bg-zinc-700 dark:text-zinc-200">
                                    {{ $voucher->code }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-300">{{ $voucher->name }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-500">
                                {{ $voucher->type === 'percentage' ? 'Persentase' : 'Nominal' }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-green-600 dark:text-green-400">
                                {{ $voucher->type === 'percentage'
                                    ? $voucher->value.'%'
                                    : 'Rp '.number_format($voucher->value, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-zinc-500">
                                {{ $voucher->quota !== null ? $voucher->quota - ($voucher->used_count ?? 0) : '∞' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($expired)
                                    <span class="text-red-500">Kedaluwarsa</span>
                                @elseif($voucher->expired_at)
                                    <span class="text-zinc-500">{{ $voucher->expired_at->format('d M Y') }}</span>
                                @else
                                    <span class="text-zinc-400">Tanpa batas</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <flux:button size="sm" variant="danger" icon="trash"
                                    wire:click="delete({{ $voucher->id }})"
                                    wire:confirm="Hapus voucher '{{ $voucher->code }}'?">Hapus</flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-sm text-zinc-400">Belum ada voucher.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $vouchers->links() }}</div>

</div>
