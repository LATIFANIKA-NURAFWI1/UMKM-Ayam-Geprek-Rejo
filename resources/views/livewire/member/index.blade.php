<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

    @if(session('status'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed right-6 top-6 z-50 flex items-center gap-3 rounded-2xl bg-green-500 px-5 py-3.5 text-white shadow-2xl">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span class="text-sm font-semibold">{{ session('status') }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div>
        <flux:heading size="xl" level="1">Manajemen Member (CRM)</flux:heading>
        <flux:text class="mt-1">Pantau loyalitas, poin, dan riwayat pembelian pelanggan</flux:text>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama / no. HP…"
                class="w-full rounded-xl border border-zinc-300 py-2 pl-10 pr-4 text-sm text-zinc-700 placeholder-zinc-400 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
        </div>
        <select wire:model.live="filterStatus"
            class="rounded-xl border border-zinc-300 px-4 py-2 text-sm text-zinc-700 focus:border-orange-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Nonaktif</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
            <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Member</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Poin</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Total Belanja</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500">Pesanan</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500">Status</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($members as $member)
                    <tr class="group transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-orange-100 text-lg font-bold text-orange-600">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-zinc-900 dark:text-white">{{ $member->name }}</p>
                                    <p class="text-xs text-zinc-400">📱 {{ $member->phone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <span class="text-sm font-bold text-orange-600">
                                {{ number_format($member->points, 0, ',', '.') }}
                            </span>
                            <p class="text-xs text-zinc-400">poin</p>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <span class="text-sm font-bold text-emerald-700">
                                Rp {{ number_format($member->total_spent, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                            {{ $member->total_orders }}x
                        </td>
                        <td class="px-5 py-4 text-center">
                            <button wire:click="toggleActive({{ $member->id }})">
                                @if($member->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 transition hover:bg-emerald-200">✅ Aktif</span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-zinc-100 px-3 py-1 text-xs font-bold text-zinc-500 transition hover:bg-zinc-200">⏸ Nonaktif</span>
                                @endif
                            </button>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <button wire:click="viewMember({{ $member->id }})"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-100 active:scale-95">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <span class="text-5xl">👥</span>
                                <p class="font-semibold text-zinc-500">Belum ada member</p>
                                <p class="text-sm text-zinc-400">Member akan terdaftar saat pelanggan mendaftar saat checkout</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($members->hasPages())
            <div class="border-t border-zinc-100 px-5 py-4 dark:border-zinc-800">{{ $members->links() }}</div>
        @endif
    </div>


    {{-- ═══════════════ SLIDE-OVER: DETAIL MEMBER ═══════════════ --}}
    <div
        x-data="{ open: @entangle('viewingMemberId').live !== null }"
        x-show="$wire.viewingMemberId !== null"
        class="fixed inset-0 z-50 overflow-hidden"
        style="display: none;"
    >
        <div class="absolute inset-0 overflow-hidden">
            <div x-show="$wire.viewingMemberId !== null"
                x-transition:enter="transition ease-in-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                x-on:click="$wire.closeDetail()"></div>

            <div class="pointer-events-none absolute inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="$wire.viewingMemberId !== null"
                    x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-lg">
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl dark:bg-zinc-900">

                        @if($this->viewingMember)
                            @php $m = $this->viewingMember; @endphp

                            {{-- Header --}}
                            <div class="bg-gradient-to-r from-orange-500 to-amber-400 px-6 py-8 text-white">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/20 text-3xl font-black">
                                            {{ strtoupper(substr($m->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-black">{{ $m->name }}</h2>
                                            <p class="opacity-80">📱 {{ $m->phone }}</p>
                                            <p class="mt-1 text-sm opacity-70">
                                                Member sejak {{ $m->created_at->translatedFormat('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <button wire:click="closeDetail" class="rounded-full bg-white/20 p-2 transition hover:bg-white/30">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>

                                <div class="mt-6 grid grid-cols-3 gap-3">
                                    <div class="rounded-xl bg-white/20 p-3 text-center">
                                        <p class="text-2xl font-black">{{ number_format($m->points) }}</p>
                                        <p class="text-xs opacity-80">Poin</p>
                                    </div>
                                    <div class="rounded-xl bg-white/20 p-3 text-center">
                                        <p class="text-2xl font-black">{{ $m->total_orders }}</p>
                                        <p class="text-xs opacity-80">Pesanan</p>
                                    </div>
                                    <div class="rounded-xl bg-white/20 p-3 text-center">
                                        <p class="text-lg font-black">{{ number_format($m->total_spent / 1000) }}K</p>
                                        <p class="text-xs opacity-80">Total Belanja</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Riwayat Pesanan --}}
                            <div class="flex-1 px-6 py-5 space-y-5">
                                <div>
                                    <h3 class="mb-3 text-sm font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wide">10 Pesanan Terakhir</h3>
                                    <div class="space-y-2">
                                        @forelse($m->orders as $order)
                                            <div class="flex items-center justify-between rounded-xl border border-zinc-100 bg-zinc-50 px-4 py-3 dark:border-zinc-800 dark:bg-zinc-800/40">
                                                <div>
                                                    <p class="font-mono text-xs font-semibold text-zinc-700 dark:text-zinc-300">{{ $order->order_number }}</p>
                                                    <p class="text-xs text-zinc-400">{{ $order->created_at->translatedFormat('d M Y H:i') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm font-bold text-emerald-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                                    <span @class([
                                                        'inline-block rounded-full px-2 py-0.5 text-xs font-semibold',
                                                        'bg-emerald-100 text-emerald-700' => in_array($order->status, ['confirmed', 'preparing', 'completed']),
                                                        'bg-red-100 text-red-700' => $order->status === 'cancelled',
                                                        'bg-amber-100 text-amber-700' => $order->status === 'pending',
                                                    ])>{{ ucfirst($order->status) }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-sm text-zinc-400">Belum ada pesanan</p>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Riwayat Poin --}}
                                <div>
                                    <h3 class="mb-3 text-sm font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wide">10 Log Poin Terakhir</h3>
                                    <div class="space-y-2">
                                        @forelse($m->pointLogs as $log)
                                            <div class="flex items-center justify-between rounded-xl border border-zinc-100 bg-zinc-50 px-4 py-3 dark:border-zinc-800 dark:bg-zinc-800/40">
                                                <div>
                                                    <p class="text-xs font-medium text-zinc-600 dark:text-zinc-300">{{ $log->notes ?? ucfirst($log->type) }}</p>
                                                    <p class="text-xs text-zinc-400">{{ $log->created_at->translatedFormat('d M Y H:i') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p @class(['text-sm font-bold', 'text-emerald-600' => $log->delta > 0, 'text-red-600' => $log->delta < 0])>
                                                        {{ $log->delta > 0 ? '+' : '' }}{{ $log->delta }} poin
                                                    </p>
                                                    <p class="text-xs text-zinc-400">Saldo: {{ $log->balance }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-sm text-zinc-400">Belum ada riwayat poin</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>




</div>
