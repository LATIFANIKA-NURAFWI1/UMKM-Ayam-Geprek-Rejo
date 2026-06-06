<div wire:poll.5s class="flex h-screen flex-col overflow-hidden bg-gray-950">

    @php
        $confirmed = $this->kitchenOrders->where('status', 'confirmed')->values();
        $preparing = $this->kitchenOrders->where('status', 'preparing')->values();
    @endphp

    {{-- ═══════════════════════════════════════════════════════════════════════
         TOP BAR
    ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="flex shrink-0 items-center justify-between border-b border-gray-800 bg-gray-900 px-6 py-3">
        <div class="flex items-center gap-3">
            <span class="text-3xl leading-none">🍳</span>
            <div>
                <p class="text-lg font-extrabold uppercase tracking-widest text-white">Dapur — Geprek Rejo</p>
                <p class="text-xs font-medium tracking-wider text-gray-500">Kitchen Display System</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <p
                    class="font-mono text-2xl font-bold tabular-nums text-orange-400"
                    x-data="{
                        time: '',
                        init() {
                            this.tick();
                            setInterval(() => this.tick(), 1000);
                        },
                        tick() {
                            const d = new Date();
                            this.time = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
                        }
                    }"
                    x-text="time"
                >
                    --:--:--
                </p>
                <p class="text-xs text-gray-600">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         MAIN COLUMNS
    ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="flex min-h-0 flex-1">

        {{-- ───────────────────────────────────────────────────────────────────
             COLUMN 1 — ANTRIAN MASAK (confirmed)
        ─────────────────────────────────────────────────────────────────── --}}
        <div class="flex w-1/2 flex-col border-r border-gray-800">

            {{-- Column Header --}}
            <div class="shrink-0 bg-orange-600 px-5 py-3.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🟡</span>
                        <h2 class="text-lg font-black uppercase tracking-widest text-white">Antrian Masak</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-white/20 px-4 py-1 text-xl font-black text-white">
                            {{ $confirmed->count() }}
                        </span>
                        <span class="text-xs font-medium text-orange-200">pesanan</span>
                    </div>
                </div>
            </div>

            {{-- Cards --}}
            <div class="flex-1 space-y-3 overflow-y-auto p-4">
                @forelse($confirmed as $order)
                    <div class="overflow-hidden rounded-2xl border border-orange-800/40 bg-gray-900 shadow-lg transition hover:border-orange-600/60">

                        {{-- Card Top --}}
                        <div class="flex items-start justify-between border-b border-gray-800 bg-gray-800/60 px-5 py-3">
                            <div class="flex items-end gap-3">
                                <span class="text-7xl font-black leading-none text-orange-400">
                                    #{{ $order->queue_number }}
                                </span>
                                <div class="pb-1">
                                    <p class="font-mono text-xs text-gray-500">{{ $order->order_number }}</p>
                                    @if($order->type === 'dine_in')
                                        <span class="mt-1 inline-block rounded-lg bg-gray-700 px-2.5 py-0.5 text-xs font-medium text-gray-300">
                                            🪑 Meja {{ $order->table_number ?: '?' }}
                                        </span>
                                    @else
                                        <span class="mt-1 inline-block rounded-lg bg-purple-900/50 px-2.5 py-0.5 text-xs font-medium text-purple-300">
                                            📦 Take Away
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                @if($order->confirmed_at)
                                    <div class="rounded-xl bg-orange-900/40 px-3 py-2 text-center">
                                        <p class="text-xs text-orange-400">⏱ Menunggu</p>
                                        <p class="mt-0.5 font-mono text-base font-bold text-orange-300">
                                            {{ $order->confirmed_at->diffForHumans(null, true) }}
                                        </p>
                                        <p class="text-xs text-gray-500">sejak {{ $order->confirmed_at->format('H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Items --}}
                        <div class="px-5 py-3">
                            <div class="space-y-2">
                                @foreach($order->details as $detail)
                                    <div class="flex items-center justify-between rounded-xl bg-gray-800 px-4 py-2.5">
                                        <span class="text-base font-semibold text-gray-100">
                                            {{ $detail->menu_item_name }}
                                        </span>
                                        <span class="rounded-full bg-orange-500 px-3 py-0.5 text-sm font-black text-white">
                                            ×{{ $detail->quantity }}
                                        </span>
                                    </div>
                                    @if($detail->notes)
                                        <p class="pl-4 text-xs text-yellow-400">
                                            📝 {{ $detail->notes }}
                                        </p>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Action --}}
                        <div class="px-5 pb-4">
                            <button
                                wire:click="startPreparing({{ $order->id }})"
                                wire:loading.attr="disabled"
                                wire:target="startPreparing({{ $order->id }})"
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-orange-500 px-5 py-3.5 text-lg font-black text-white shadow-md transition hover:bg-orange-400 active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <span wire:loading.remove wire:target="startPreparing({{ $order->id }})">
                                    ▶ Mulai Masak
                                </span>
                                <span wire:loading wire:target="startPreparing({{ $order->id }})" class="flex items-center gap-2">
                                    <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>

                    </div>
                @empty
                    <div class="flex h-full flex-col items-center justify-center py-20">
                        <span class="text-7xl">🎉</span>
                        <p class="mt-5 text-xl font-bold text-gray-500">Tidak ada antrian</p>
                        <p class="mt-1 text-sm text-gray-600">Semua pesanan sudah mulai dimasak</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ───────────────────────────────────────────────────────────────────
             COLUMN 2 — SEDANG DIMASAK (preparing)
        ─────────────────────────────────────────────────────────────────── --}}
        <div class="flex w-1/2 flex-col">

            {{-- Column Header --}}
            <div class="shrink-0 bg-blue-700 px-5 py-3.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🔵</span>
                        <h2 class="text-lg font-black uppercase tracking-widest text-white">Sedang Dimasak</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-white/20 px-4 py-1 text-xl font-black text-white">
                            {{ $preparing->count() }}
                        </span>
                        <span class="text-xs font-medium text-blue-200">pesanan</span>
                    </div>
                </div>
            </div>

            {{-- Cards --}}
            <div class="flex-1 space-y-3 overflow-y-auto p-4">
                @forelse($preparing as $order)
                    <div class="overflow-hidden rounded-2xl border border-blue-800/40 bg-gray-900 shadow-lg transition hover:border-blue-600/60">

                        {{-- Card Top --}}
                        <div class="flex items-start justify-between border-b border-gray-800 bg-gray-800/60 px-5 py-3">
                            <div class="flex items-end gap-3">
                                <span class="text-7xl font-black leading-none text-blue-400">
                                    #{{ $order->queue_number }}
                                </span>
                                <div class="pb-1">
                                    <p class="font-mono text-xs text-gray-500">{{ $order->order_number }}</p>
                                    @if($order->type === 'dine_in')
                                        <span class="mt-1 inline-block rounded-lg bg-gray-700 px-2.5 py-0.5 text-xs font-medium text-gray-300">
                                            🪑 Meja {{ $order->table_number ?: '?' }}
                                        </span>
                                    @else
                                        <span class="mt-1 inline-block rounded-lg bg-purple-900/50 px-2.5 py-0.5 text-xs font-medium text-purple-300">
                                            📦 Take Away
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                @if($order->confirmed_at)
                                    <div class="rounded-xl bg-blue-900/40 px-3 py-2 text-center">
                                        <p class="text-xs text-blue-400">🍳 Dimasak</p>
                                        <p class="mt-0.5 font-mono text-base font-bold text-blue-300">
                                            {{ $order->confirmed_at->diffForHumans(null, true) }}
                                        </p>
                                        <p class="text-xs text-gray-500">sejak {{ $order->confirmed_at->format('H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Items --}}
                        <div class="px-5 py-3">
                            <div class="space-y-2">
                                @foreach($order->details as $detail)
                                    <div class="flex items-center justify-between rounded-xl bg-gray-800 px-4 py-2.5">
                                        <span class="text-base font-semibold text-gray-100">
                                            {{ $detail->menu_item_name }}
                                        </span>
                                        <span class="rounded-full bg-blue-500 px-3 py-0.5 text-sm font-black text-white">
                                            ×{{ $detail->quantity }}
                                        </span>
                                    </div>
                                    @if($detail->notes)
                                        <p class="pl-4 text-xs text-yellow-400">
                                            📝 {{ $detail->notes }}
                                        </p>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Action --}}
                        <div class="px-5 pb-4">
                            <button
                                wire:click="completeOrder({{ $order->id }})"
                                wire:loading.attr="disabled"
                                wire:target="completeOrder({{ $order->id }})"
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-green-600 px-5 py-3.5 text-lg font-black text-white shadow-md transition hover:bg-green-500 active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <span wire:loading.remove wire:target="completeOrder({{ $order->id }})">
                                    ✅ Selesai
                                </span>
                                <span wire:loading wire:target="completeOrder({{ $order->id }})" class="flex items-center gap-2">
                                    <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>

                    </div>
                @empty
                    <div class="flex h-full flex-col items-center justify-center py-20">
                        <span class="text-7xl">✨</span>
                        <p class="mt-5 text-xl font-bold text-gray-500">Tidak ada yang dimasak</p>
                        <p class="mt-1 text-sm text-gray-600">Tekan "Mulai Masak" dari kolom antrian</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
