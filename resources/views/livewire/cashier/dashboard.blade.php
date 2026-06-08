<div wire:poll.3s class="min-h-screen bg-gray-100">

    {{-- ═══════════════════════════════════════════════════════════════════════
         FLASH MESSAGES
    ═══════════════════════════════════════════════════════════════════════ --}}
    @if(session('status'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="fixed right-4 top-4 z-50 flex items-center gap-3 rounded-xl bg-green-500 px-5 py-3 text-white shadow-xl"
        >
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-sm font-semibold">{{ session('status') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="fixed right-4 top-4 z-50 flex max-w-sm items-start gap-3 rounded-xl bg-red-500 px-5 py-3 text-white shadow-xl"
        >
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <span class="text-sm font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════════
         HEADER & STATS BAR
    ═══════════════════════════════════════════════════════════════════════ --}}
    <header class="sticky top-0 z-20 border-b border-gray-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-3">

            {{-- Title --}}
            <div class="flex items-center gap-3">
                <span class="text-2xl leading-none">🧾</span>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Dashboard Kasir</h1>
                    <p class="text-xs text-gray-400">Geprek Rejo &bull; {{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            {{-- Stats --}}
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-2 rounded-lg bg-orange-100 px-4 py-2">
                    <span class="text-sm font-semibold text-orange-700">
                        ⏳ Pending:
                        <span class="ml-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-orange-500 text-xs font-black text-white">
                            {{ $this->pendingOrders->count() }}
                        </span>
                    </span>
                </div>

                <div class="flex items-center gap-2 rounded-lg bg-blue-100 px-4 py-2">
                    <span class="text-sm font-semibold text-blue-700">
                        👨‍🍳 Diproses:
                        <span class="ml-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-500 text-xs font-black text-white">
                            {{ $this->confirmedOrders->count() }}
                        </span>
                    </span>
                </div>

                <div class="rounded-lg bg-gray-100 px-4 py-2">
                    <span
                        class="font-mono text-sm font-medium text-gray-600"
                        x-data="{
                            time: '',
                            init() {
                                this.tick();
                                setInterval(() => this.tick(), 1000);
                            },
                            tick() {
                                const d = new Date();
                                this.time = '🕐 ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
                            }
                        }"
                        x-text="time"
                    >🕐 --:--:--</span>
                </div>

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-500 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <main class="p-5">

        {{-- ═══════════════════════════════════════════════════════════════════
             SECTION 1 — PENDING ORDERS (Menunggu Konfirmasi Pembayaran)
        ═══════════════════════════════════════════════════════════════════ --}}
        <section class="mb-8">
            <div class="mb-4 flex items-center gap-3">
                <h2 class="text-lg font-bold text-gray-700">⏳ Menunggu Konfirmasi Pembayaran</h2>
                <span class="rounded-full bg-orange-500 px-2.5 py-0.5 text-xs font-black text-white">
                    {{ $this->pendingOrders->count() }}
                </span>
            </div>

            @if($this->pendingOrders->isEmpty())
                <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 bg-white py-14">
                    <span class="text-5xl">🎉</span>
                    <p class="mt-3 font-semibold text-gray-400">Tidak ada pesanan yang menunggu konfirmasi</p>
                    <p class="text-sm text-gray-300">Semua pembayaran sudah dikonfirmasi</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($this->pendingOrders as $order)
                        <div class="relative flex flex-col overflow-hidden rounded-2xl border-2 border-orange-200 bg-white shadow-sm transition hover:border-orange-300 hover:shadow-md">

                            {{-- Card Header --}}
                            <div class="flex items-start justify-between bg-gradient-to-r from-orange-50 to-amber-50 px-4 py-3">
                                <div>
                                    <span class="text-4xl font-black leading-none text-orange-600">#{{ $order->queue_number }}</span>
                                    <p class="mt-0.5 font-mono text-xs text-gray-400">{{ $order->order_number }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-500">{{ $order->created_at->format('H:i') }}</p>
                                    <p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                                    @if($order->type === 'dine_in')
                                        <span class="mt-1 inline-block rounded-lg bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                                            🪑 Meja {{ $order->table_number ?: '?' }}
                                        </span>
                                    @else
                                        <span class="mt-1 inline-block rounded-lg bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-600">
                                            📦 Take Away
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-1 flex-col gap-3 p-4">

                                {{-- Customer & Payment Badge --}}
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        @if($order->member)
                                            <p class="truncate text-sm font-semibold text-gray-800">
                                                ⭐ {{ $order->member->name }}
                                            </p>
                                            <p class="text-xs text-gray-400">{{ $order->member->phone }}</p>
                                        @else
                                            <p class="text-sm text-gray-400">👤 Pelanggan Umum</p>
                                        @endif
                                    </div>
                                    @if($order->payment_method === 'qris')
                                        <span class="shrink-0 rounded-full bg-orange-100 px-3 py-0.5 text-xs font-bold text-orange-700">QRIS</span>
                                    @else
                                        <span class="shrink-0 rounded-full bg-green-100 px-3 py-0.5 text-xs font-bold text-green-700">Tunai</span>
                                    @endif
                                </div>

                                {{-- Items List --}}
                                @php $details = $order->details; $maxShow = 3; @endphp
                                <div class="rounded-xl bg-gray-50 p-2.5">
                                    @foreach($details->take($maxShow) as $detail)
                                        <div class="flex items-center justify-between py-0.5">
                                            <span class="truncate pr-2 text-xs text-gray-700">{{ $detail->menu_item_name }}</span>
                                            <span class="shrink-0 rounded bg-gray-200 px-1.5 py-0.5 text-xs font-bold text-gray-600">
                                                ×{{ $detail->quantity }}
                                            </span>
                                        </div>
                                    @endforeach
                                    @if($details->count() > $maxShow)
                                        <p class="mt-1.5 text-center text-xs text-gray-400">
                                            + {{ $details->count() - $maxShow }} item lagi
                                        </p>
                                    @endif
                                </div>

                                {{-- Notes --}}
                                @if($order->notes)
                                    <div class="rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-2">
                                        <p class="text-xs text-yellow-700">📝 {{ $order->notes }}</p>
                                    </div>
                                @endif

                                {{-- Badge Promo Poin Member --}}
                                @if((int)$order->points_redeemed > 0)
                                    <div class="flex items-center gap-1.5 rounded-lg bg-amber-50 border border-amber-200 px-3 py-1.5">
                                        <span class="text-sm">✨</span>
                                        <span class="text-xs font-bold text-amber-700">Promo Poin Member</span>
                                        <span class="ml-auto text-xs text-amber-600">{{ $order->points_redeemed }} poin → −Rp {{ number_format($order->points_redeemed_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                {{-- Discount Info (Voucher) --}}
                                @if($order->discount_amount > 0)
                                    <div class="rounded-lg bg-green-50 px-3 py-1.5 text-xs text-green-700">
                                        <span>🎟 Voucher: -Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                {{-- Total --}}
                                <div class="flex items-center justify-between border-t border-gray-100 pt-2">
                                    <span class="text-sm text-gray-500">Total Bayar</span>
                                    <span class="text-2xl font-black text-gray-900">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </span>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex flex-col gap-2 pt-1">
                                    <div class="flex gap-2">
                                        <button
                                            wire:click="openDetailModal({{ $order->id }})"
                                            class="flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-600 transition hover:bg-gray-50 active:scale-95"
                                        >
                                            🔍 Detail
                                        </button>
                                        <button
                                            wire:click="openCancelModal({{ $order->id }})"
                                            class="flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-medium text-red-600 transition hover:bg-red-100 active:scale-95"
                                        >
                                            ❌ Batal
                                        </button>
                                    </div>

                                    <button
                                        wire:click="openConfirmModal({{ $order->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="openConfirmModal({{ $order->id }}), confirmPayment"
                                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-green-500 px-4 py-2.5 font-bold text-white shadow-sm transition hover:bg-green-600 active:scale-95 disabled:cursor-not-allowed disabled:opacity-60"
                                    >
                                        <span wire:loading.remove wire:target="openConfirmModal({{ $order->id }})">
                                            ✅ Konfirmasi Lunas
                                        </span>
                                        <span
                                            wire:loading
                                            wire:target="openConfirmModal({{ $order->id }})"
                                            class="flex items-center gap-2"
                                        >
                                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                            </svg>
                                            Memproses...
                                        </span>
                                    </button>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- ═══════════════════════════════════════════════════════════════════
             SECTION 2 — CONFIRMED ORDERS (Sedang Diproses Dapur)
        ═══════════════════════════════════════════════════════════════════ --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <h2 class="text-lg font-bold text-gray-700">👨‍🍳 Sedang Diproses Dapur</h2>
                <span class="rounded-full bg-blue-500 px-2.5 py-0.5 text-xs font-black text-white">
                    {{ $this->confirmedOrders->count() }}
                </span>
            </div>

            @if($this->confirmedOrders->isEmpty())
                <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 bg-white py-10">
                    <span class="text-4xl">🍳</span>
                    <p class="mt-3 font-semibold text-gray-400">Tidak ada pesanan yang sedang diproses</p>
                </div>
            @else
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                    @foreach($this->confirmedOrders as $order)
                        <div class="flex flex-col rounded-xl border border-blue-200 bg-white p-3 shadow-sm">

                            {{-- Header --}}
                            <div class="mb-1 flex items-center justify-between">
                                <span class="text-2xl font-black leading-none text-blue-600">#{{ $order->queue_number }}</span>
                                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-600">Konfirmasi</span>
                            </div>
                            <p class="mb-1 font-mono text-xs text-gray-400">{{ $order->order_number }}</p>

                            {{-- Customer --}}
                            @if($order->member)
                                <p class="mb-1 truncate text-xs font-medium text-gray-600">⭐ {{ $order->member->name }}</p>
                            @endif

                            {{-- Badge Promo Poin --}}
                            @if((int)$order->points_redeemed > 0)
                                <span class="mb-1 inline-block rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold text-amber-700">✨ Promo Poin</span>
                            @endif

                            {{-- Type --}}
                            @if($order->type === 'dine_in')
                                <p class="mb-2 text-xs text-gray-400">🪑 Meja {{ $order->table_number ?: '?' }}</p>
                            @else
                                <p class="mb-2 text-xs text-gray-400">📦 Take Away</p>
                            @endif

                            {{-- Items --}}
                            <div class="mb-2 flex-1 space-y-0.5 rounded-lg bg-gray-50 p-1.5">
                                @foreach($order->details->take(3) as $detail)
                                    <div class="flex items-center justify-between">
                                        <span class="truncate text-xs text-gray-600">{{ $detail->menu_item_name }}</span>
                                        <span class="ml-1 shrink-0 text-xs font-bold text-gray-500">×{{ $detail->quantity }}</span>
                                    </div>
                                @endforeach
                                @if($order->details->count() > 3)
                                    <p class="text-center text-xs text-gray-400">+{{ $order->details->count() - 3 }} lagi</p>
                                @endif
                            </div>

                            {{-- Total & Time --}}
                            <div class="border-t border-gray-100 pt-1.5 text-right">
                                <p class="text-sm font-bold text-gray-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                @if($order->confirmed_at)
                                    <p class="text-xs text-gray-400">✅ {{ $order->confirmed_at->format('H:i') }}</p>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </main>


    {{-- ═══════════════════════════════════════════════════════════════════════
         MODAL 1 — DETAIL ORDER
    ═══════════════════════════════════════════════════════════════════════ --}}
    <div
        x-data="{ open: @entangle('showDetailModal') }"
        x-show="open"
        x-on:keydown.escape.window="$wire.closeDetailModal()"
        class="fixed inset-0 z-40 flex items-end justify-center p-4 sm:items-center"
        style="display: none;"
    >
        {{-- Backdrop --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60"
            x-on:click="$wire.closeDetailModal()"
        ></div>

        {{-- Panel --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative z-50 w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl"
        >
            @if($this->selectedOrder)
                {{-- Modal Header --}}
                <div class="flex items-start justify-between bg-gray-50 px-6 py-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Detail Pesanan</h3>
                        <p class="font-mono text-sm text-gray-400">{{ $this->selectedOrder->order_number }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-4xl font-black text-gray-700">#{{ $this->selectedOrder->queue_number }}</span>
                        <button
                            wire:click="closeDetailModal"
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-gray-500 transition hover:bg-gray-300"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="max-h-[60vh] overflow-y-auto px-6 py-4">

                    {{-- Customer Info --}}
                    @if($this->selectedOrder->member)
                        <div class="mb-4 flex items-center gap-3 rounded-xl bg-yellow-50 p-3">
                            <span class="text-2xl">⭐</span>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $this->selectedOrder->member->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $this->selectedOrder->member->phone }}
                                    &bull;
                                    <span class="font-medium text-yellow-600">{{ number_format($this->selectedOrder->member->points) }} poin</span>
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="mb-4 rounded-xl bg-gray-50 p-3">
                            <p class="text-sm text-gray-400">👤 Pelanggan Umum (tanpa akun member)</p>
                        </div>
                    @endif

                    {{-- Order Info Grid --}}
                    <div class="mb-4 grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-400">Tipe</p>
                            <p class="mt-0.5 font-semibold text-gray-700">
                                {{ $this->selectedOrder->type === 'dine_in' ? '🪑 Dine In' : '📦 Take Away' }}
                            </p>
                        </div>
                        @if($this->selectedOrder->table_number)
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-400">Nomor Meja</p>
                                <p class="mt-0.5 font-semibold text-gray-700">{{ $this->selectedOrder->table_number }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-400">Metode Bayar</p>
                            <div class="mt-0.5">
                                @if($this->selectedOrder->payment_method === 'qris')
                                    <span class="inline-block rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-bold text-orange-700">📱 QRIS</span>
                                @else
                                    <span class="inline-block rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-bold text-green-700">💵 Tunai</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-400">Waktu Order</p>
                            <p class="mt-0.5 font-semibold text-gray-700">{{ $this->selectedOrder->created_at->format('d/m H:i') }}</p>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="mb-4">
                        <p class="mb-2 text-xs font-bold uppercase tracking-wide text-gray-400">Item Pesanan ({{ $this->selectedOrder->details->count() }})</p>
                        <div class="space-y-2">
                            @foreach($this->selectedOrder->details as $detail)
                                <div class="flex items-start justify-between gap-3 rounded-xl bg-gray-50 px-3.5 py-2.5">
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-800">{{ $detail->menu_item_name }}</p>
                                        @if($detail->notes)
                                            <p class="mt-0.5 text-xs text-gray-400">📝 {{ $detail->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="shrink-0 text-right">
                                        <p class="rounded bg-gray-200 px-2 py-0.5 text-xs font-bold text-gray-600">×{{ $detail->quantity }}</p>
                                        <p class="mt-1 text-sm font-semibold text-gray-700">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Notes --}}
                    @if($this->selectedOrder->notes)
                        <div class="mb-4 rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-2.5">
                            <p class="text-sm font-medium text-yellow-700">📝 {{ $this->selectedOrder->notes }}</p>
                        </div>
                    @endif

                    {{-- Pricing Summary --}}
                    <div class="space-y-2 rounded-xl bg-gray-50 p-4 text-sm">
                        <div class="flex justify-between text-gray-500">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($this->selectedOrder->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($this->selectedOrder->discount_amount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>🎟 Diskon Voucher</span>
                                <span>− Rp {{ number_format($this->selectedOrder->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($this->selectedOrder->points_redeemed_amount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>⭐ Redeem Poin ({{ $this->selectedOrder->points_redeemed }} poin)</span>
                                <span>− Rp {{ number_format($this->selectedOrder->points_redeemed_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between border-t border-gray-200 pt-2 text-base font-black text-gray-900">
                            <span>Total</span>
                            <span>Rp {{ number_format($this->selectedOrder->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end gap-2 border-t border-gray-100 px-6 py-4">
                    <button
                        wire:click="closeDetailModal"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50"
                    >
                        Tutup
                    </button>
                    @if($this->selectedOrder->isPending())
                        <button
                            wire:click="openConfirmModal({{ $this->selectedOrder->id }})"
                            class="rounded-lg bg-green-500 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-green-600"
                        >
                            ✅ Konfirmasi Lunas
                        </button>
                    @endif
                </div>
            @else
                <div class="flex items-center justify-center p-16">
                    <svg class="h-8 w-8 animate-spin text-gray-300" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                </div>
            @endif
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════════════
         MODAL 2 — KONFIRMASI PEMBAYARAN
    ═══════════════════════════════════════════════════════════════════════ --}}
    <div
        x-data
        x-show="$wire.confirmingOrderId !== null"
        x-on:keydown.escape.window="$wire.set('confirmingOrderId', null)"
        class="fixed inset-0 z-40 flex items-end justify-center p-4 sm:items-center"
        style="display: none;"
    >
        {{-- Backdrop --}}
        <div
            x-show="$wire.confirmingOrderId !== null"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60"
            x-on:click="$wire.set('confirmingOrderId', null)"
        ></div>

        {{-- Panel --}}
        @if($this->confirmingOrderId)
            @php
                $confirmOrder = $this->pendingOrders->firstWhere('id', $this->confirmingOrderId);
            @endphp
            @if($confirmOrder)
                <div
                    x-show="$wire.confirmingOrderId !== null"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative z-50 w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl"
                >
                    {{-- Header --}}
                    <div class="flex items-center gap-4 border-b border-gray-100 px-6 py-5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-green-100 text-2xl">💳</div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Konfirmasi Pembayaran</h3>
                            <p class="font-mono text-sm text-gray-400">
                                #{{ $confirmOrder->queue_number }} &bull; {{ $confirmOrder->order_number }}
                            </p>
                        </div>
                    </div>

                    <div class="px-6 py-5">
                        {{-- Total --}}
                        <div class="mb-6 rounded-2xl bg-gray-50 p-5 text-center">
                            <p class="text-sm text-gray-500">Total yang harus dibayar</p>
                            <p class="mt-1 text-5xl font-black tabular-nums text-gray-900">
                                Rp {{ number_format($confirmOrder->total_amount, 0, ',', '.') }}
                            </p>
                            @if($confirmOrder->discount_amount > 0 || $confirmOrder->points_redeemed_amount > 0)
                                <p class="mt-1.5 text-xs text-green-600">
                                    Sudah termasuk potongan
                                    @if($confirmOrder->discount_amount > 0) voucher @endif
                                    @if($confirmOrder->discount_amount > 0 && $confirmOrder->points_redeemed_amount > 0) & @endif
                                    @if($confirmOrder->points_redeemed_amount > 0) poin @endif
                                </p>
                            @endif
                        </div>

                        {{-- Payment Method Info (Read Only) --}}
                        <div class="mb-2">
                            <p class="mb-2 text-sm font-bold text-gray-600">Metode Pembayaran</p>
                            @if($confirmOrder->payment_method === 'qris')
                                <div class="flex items-center gap-3 rounded-2xl border-2 border-orange-200 bg-orange-50 p-4">
                                    <span class="text-4xl">📱</span>
                                    <div>
                                        <p class="text-sm font-bold text-orange-800">QRIS Statis</p>
                                        <p class="text-xs text-orange-600">Pelanggan membayar via Scan QRIS</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-3 rounded-2xl border-2 border-green-200 bg-green-50 p-4">
                                    <span class="text-4xl">💵</span>
                                    <div>
                                        <p class="text-sm font-bold text-green-800">Tunai (Cash)</p>
                                        <p class="text-xs text-green-600">Terima pembayaran tunai di kasir</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex gap-3 border-t border-gray-100 px-6 py-4">
                        <button
                            wire:click="$set('confirmingOrderId', null)"
                            class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                        >
                            Batal
                        </button>
                        <button
                            wire:click="confirmPayment"
                            wire:loading.attr="disabled"
                            wire:target="confirmPayment"
                            class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-green-500 px-4 py-3 text-sm font-bold text-white transition hover:bg-green-600 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <span wire:loading.remove wire:target="confirmPayment">✅ Konfirmasi Lunas</span>
                            <span wire:loading wire:target="confirmPayment" class="flex items-center gap-2">
                                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            @endif
        @endif
    </div>


    {{-- ═══════════════════════════════════════════════════════════════════════
         MODAL 3 — BATALKAN ORDER
    ═══════════════════════════════════════════════════════════════════════ --}}
    <div
        x-data="{ open: @entangle('showCancelModal') }"
        x-show="open"
        x-on:keydown.escape.window="$wire.closeCancelModal()"
        class="fixed inset-0 z-40 flex items-end justify-center p-4 sm:items-center"
        style="display: none;"
    >
        {{-- Backdrop --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60"
            x-on:click="$wire.closeCancelModal()"
        ></div>

        {{-- Panel --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative z-50 w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl"
        >
            {{-- Header --}}
            <div class="flex items-center gap-4 border-b border-gray-100 px-6 py-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-100 text-2xl">⚠️</div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Batalkan Pesanan</h3>
                    <p class="text-sm text-gray-400">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5">
                <label class="block">
                    <span class="mb-2 block text-sm font-bold text-gray-600">
                        Alasan Pembatalan
                        <span class="ml-0.5 text-red-500">*</span>
                    </span>
                    <textarea
                        wire:model="cancelReason"
                        rows="4"
                        placeholder="Contoh: Pelanggan membatalkan pesanan, stok habis, dll. (min. 3 karakter)"
                        class="w-full resize-none rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-700 placeholder-gray-400 outline-none transition focus:border-red-400 focus:ring-2 focus:ring-red-100"
                    ></textarea>
                    @error('cancelReason')
                        <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-red-500">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </label>
            </div>

            {{-- Footer --}}
            <div class="flex gap-3 border-t border-gray-100 px-6 py-4">
                <button
                    wire:click="closeCancelModal"
                    class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                >
                    Tutup
                </button>
                <button
                    wire:click="cancelOrder"
                    wire:loading.attr="disabled"
                    wire:target="cancelOrder"
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-red-500 px-4 py-3 text-sm font-bold text-white transition hover:bg-red-600 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="cancelOrder">Batalkan Pesanan</span>
                    <span wire:loading wire:target="cancelOrder" class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>

</div>
