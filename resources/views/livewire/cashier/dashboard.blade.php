<div wire:poll.5s.keep-alive class="min-h-screen bg-[#f7f9ff] text-[#181c20]">

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
            class="fixed right-4 top-4 z-50 flex items-center gap-3 rounded-xl bg-emerald-500 px-5 py-3 text-white shadow-xl"
        >
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="font-inter text-sm font-semibold">{{ session('status') }}</span>
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
            class="fixed right-4 top-4 z-50 flex max-w-sm items-start gap-3 rounded-xl bg-[#ba1a1a] px-5 py-3 text-white shadow-xl"
        >
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <span class="font-inter text-sm font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════════
         TOP APP BAR
    ═══════════════════════════════════════════════════════════════════════ --}}
    <header class="bg-[#f7f9ff] border-b border-[#e8bcb6] shadow-sm w-full sticky top-0 z-40">
        <div class="flex justify-between items-center w-full px-6 py-4">
            <div class="flex items-center gap-4">
                <span class="font-jakarta text-[24px] leading-[32px] font-bold text-[#181c20]">DAPUR — GEPREK REJO</span>
                <span class="bg-[#e5e8ee] px-3 py-1 rounded-full font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold text-[#5e3f3b] hidden md:inline-block">DASHBOARD KASIR</span>
            </div>
            <div class="flex items-center gap-4">
                {{-- Clock --}}
                <span
                    class="font-inter text-sm font-medium text-[#5e3f3b] hidden md:flex items-center gap-1"
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
                >
                    <span class="material-symbols-outlined text-[18px]">schedule</span>
                    <span x-text="time">--:--:--</span>
                </span>

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="bg-[#bc000a] text-white font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold px-4 py-2 rounded-full hover:bg-[#c0000b] transition-colors duration-200 shadow-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">logout</span>
                            Keluar
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    {{-- ═══════════════════════════════════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════════════════════════════════ --}}
    <main class="flex-grow p-6">

        {{-- Status Filter Pills --}}
        <div class="flex gap-4 mb-8 overflow-x-auto pb-2 scrollbar-hide">
            <button class="bg-[#fdc003] text-[#6c5000] px-4 py-2 rounded-full font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold flex items-center gap-2 shadow-sm border border-[#785900] transition-all hover:bg-[#fabd00] shrink-0">
                <span class="material-symbols-outlined text-[16px]">pending_actions</span>
                Menunggu Pembayaran
                <span class="bg-[#6c5000] text-[#fdc003] px-2 rounded-full ml-1">{{ $this->pendingOrders->count() }}</span>
            </button>
            <button class="bg-[#f7f9ff] text-[#181c20] px-4 py-2 rounded-full font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold flex items-center gap-2 border border-[#e8bcb6] hover:bg-[#e0e3e8] transition-all shrink-0">
                <span class="material-symbols-outlined text-[16px]">outdoor_grill</span>
                Diproses Dapur
                <span class="bg-[#e0e3e8] text-[#181c20] px-2 rounded-full ml-1">{{ $this->confirmedOrders->count() }}</span>
            </button>
        </div>

        {{-- 2-Column Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- ═══════════════════════════════════════════════════════════════
                 COLUMN 1: Menunggu Konfirmasi Pembayaran
            ═══════════════════════════════════════════════════════════════ --}}
            <section class="flex flex-col gap-4">
                <div class="flex items-center justify-between border-b-2 border-[#bc000a] pb-2 mb-2">
                    <h2 class="font-jakarta text-[20px] leading-[28px] font-semibold text-[#bc000a] flex items-center gap-2">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">payments</span>
                        Menunggu Pembayaran
                    </h2>
                    <span class="bg-[#bc000a] text-white font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold px-2 py-1 rounded-full">{{ $this->pendingOrders->count() }}</span>
                </div>

                @if($this->pendingOrders->isEmpty())
                    <div class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-[#e8bcb6] rounded-xl bg-[#f1f4f9] h-48 opacity-60">
                        <span class="text-5xl mb-2">🎉</span>
                        <p class="font-inter text-[14px] leading-[20px] text-[#5e3f3b] text-center font-medium">Tidak ada pesanan yang menunggu konfirmasi</p>
                        <p class="font-inter text-[12px] text-[#936e69]">Semua pembayaran sudah dikonfirmasi</p>
                    </div>
                @else
                    @foreach($this->pendingOrders as $order)
                        <article class="bg-[#f7f9ff] rounded-xl border border-[#e8bcb6] shadow-sm p-5 flex flex-col gap-4 hover:shadow-md transition-shadow relative overflow-hidden group">
                            {{-- Decorative corner --}}
                            <div class="absolute top-0 right-0 w-16 h-16 bg-[#e61919] rounded-bl-full -z-0 opacity-20"></div>

                            {{-- Header: Order number, payment badge, time, table --}}
                            <div class="flex justify-between items-start z-10">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        @if($order->payment_method === 'qris')
                                            <span class="bg-[#fdc003] text-[#6c5000] font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold px-2 py-0.5 rounded">QRIS</span>
                                        @else
                                            <span class="bg-[#e0e3e8] text-[#181c20] font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold px-2 py-0.5 rounded border border-[#e8bcb6]">TUNAI</span>
                                        @endif
                                        <span class="text-[#5e3f3b] font-inter text-[14px] leading-[20px] flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span> {{ $order->created_at->format('H:i') }}
                                        </span>
                                    </div>
                                    <h3 class="font-jakarta text-[48px] leading-[1.2] tracking-[-0.02em] font-extrabold text-[#181c20]">#{{ $order->queue_number }}</h3>
                                </div>
                                <div class="text-right">
                                    @if($order->type === 'dine_in')
                                        <span class="block font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold text-[#5e3f3b] mb-1">MEJA</span>
                                        <span class="font-jakarta text-[20px] leading-[28px] font-semibold text-[#bc000a] bg-[#e61919]/20 px-3 py-1 rounded-lg">M-{{ $order->table_number ?: '?' }}</span>
                                    @else
                                        <span class="block font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold text-[#5e3f3b] mb-1">BUNGKUS</span>
                                        <span class="font-jakarta text-[20px] leading-[28px] font-semibold text-[#181c20] bg-[#e5e8ee] px-3 py-1 rounded-lg">TA</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Customer info --}}
                            @if($order->member)
                                <div class="flex items-center gap-2 text-[14px] z-10">
                                    <span>⭐</span>
                                    <span class="font-inter font-semibold text-[#181c20] truncate">{{ $order->member->name }}</span>
                                    <span class="font-inter text-[12px] text-[#5e3f3b]">{{ $order->member->phone }}</span>
                                </div>
                            @endif

                            {{-- Items List --}}
                            @php $details = $order->details; $maxShow = 3; @endphp
                            <div class="border-t border-dashed border-[#e8bcb6] pt-3 z-10">
                                <ul class="flex flex-col gap-2 font-inter text-[14px] leading-[20px] text-[#181c20]">
                                    @foreach($details->take($maxShow) as $detail)
                                        <li class="flex justify-between">
                                            <span>{{ $detail->quantity }}x {{ $detail->menu_item_name }}</span>
                                            <span class="font-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                @if($details->count() > $maxShow)
                                    <p class="mt-1.5 text-center text-xs text-[#936e69]">+ {{ $details->count() - $maxShow }} item lagi</p>
                                @endif
                            </div>

                            {{-- Notes --}}
                            @if($order->notes)
                                <div class="rounded-lg border border-[#ffdf9e] bg-[#ffdf9e]/20 px-3 py-2 z-10">
                                    <p class="font-inter text-xs text-[#5b4300]">📝 {{ $order->notes }}</p>
                                </div>
                            @endif

                            {{-- Badge Promo Poin Member --}}
                            @if((int)$order->points_redeemed > 0)
                                <div class="flex items-center gap-1.5 rounded-lg bg-[#ffdf9e]/20 border border-[#ffdf9e] px-3 py-1.5 z-10">
                                    <span class="text-sm">✨</span>
                                    <span class="font-inter text-xs font-bold text-[#5b4300]">Promo Poin Member</span>
                                    <span class="ml-auto font-inter text-xs text-[#785900]">{{ $order->points_redeemed }} poin → −Rp {{ number_format($order->points_redeemed_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            {{-- Discount Info (Voucher) --}}
                            @if($order->discount_amount > 0)
                                <div class="rounded-lg bg-emerald-50 px-3 py-1.5 text-xs text-emerald-700 z-10">
                                    <span>🎟 Voucher: -Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            {{-- Total --}}
                            <div class="border-t border-[#e8bcb6] pt-3 flex justify-between items-center z-10">
                                <div>
                                    <span class="block font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold text-[#5e3f3b]">TOTAL TAGIHAN</span>
                                    <span class="font-jakarta text-[24px] leading-[32px] font-bold text-[#bc000a]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex gap-2 mt-2 z-10">
                                <button
                                    wire:click="openCancelModal({{ $order->id }})"
                                    class="flex-1 bg-[#f7f9ff] border border-[#936e69] text-[#181c20] font-jakarta text-[16px] leading-[24px] font-medium py-3 rounded-lg hover:bg-[#ffdad6] hover:text-[#93000a] hover:border-[#ba1a1a] transition-colors"
                                >
                                    Batal
                                </button>
                                <button
                                    wire:click="openConfirmModal({{ $order->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="openConfirmModal({{ $order->id }}), confirmPayment"
                                    class="flex-[2] bg-gradient-to-r from-emerald-600 to-emerald-500 text-white font-jakarta text-[16px] leading-[24px] font-bold py-3 rounded-lg shadow-sm hover:shadow-md transition-shadow flex items-center justify-center gap-2 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    <span wire:loading.remove wire:target="openConfirmModal({{ $order->id }})">
                                        <span class="material-symbols-outlined text-[18px] align-middle">check_circle</span>
                                        Konfirmasi Lunas
                                    </span>
                                    <span wire:loading wire:target="openConfirmModal({{ $order->id }})" class="flex items-center gap-2">
                                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                        </svg>
                                        Memproses...
                                    </span>
                                </button>
                            </div>
                        </article>
                    @endforeach
                @endif
            </section>

            {{-- ═══════════════════════════════════════════════════════════════
                 COLUMN 2: Sedang Diproses Dapur
            ═══════════════════════════════════════════════════════════════ --}}
            <section class="flex flex-col gap-4">
                <div class="flex items-center justify-between border-b-2 border-[#fdc003] pb-2 mb-2">
                    <h2 class="font-jakarta text-[20px] leading-[28px] font-semibold text-[#181c20] flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#fdc003]" style="font-variation-settings: 'FILL' 1;">soup_kitchen</span>
                        Diproses Dapur
                    </h2>
                    <span class="bg-[#fdc003] text-[#6c5000] font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold px-2 py-1 rounded-full">{{ $this->confirmedOrders->count() }}</span>
                </div>

                @if($this->confirmedOrders->isEmpty())
                    <div class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-[#e8bcb6] rounded-xl bg-[#f1f4f9] h-48 opacity-60">
                        <span class="material-symbols-outlined text-4xl text-[#936e69] mb-2">restaurant</span>
                        <p class="font-inter text-[14px] leading-[20px] text-[#5e3f3b] text-center">Tidak ada pesanan yang sedang diproses.</p>
                    </div>
                @else
                    @foreach($this->confirmedOrders as $order)
                        <article class="bg-[#f7f9ff] rounded-xl border border-[#e8bcb6] shadow-sm p-5 flex flex-col gap-4 hover:shadow-md transition-shadow relative overflow-hidden group">
                            <div class="flex justify-between items-start z-10">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="bg-[#e0e3e8] text-[#181c20] font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold px-2 py-0.5 rounded border border-[#e8bcb6]">LUNAS</span>
                                        <span class="text-[#5e3f3b] font-inter text-[14px] leading-[20px] flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                                            {{ $order->confirmed_at ? $order->confirmed_at->format('H:i') : $order->created_at->format('H:i') }}
                                        </span>
                                    </div>
                                    <h3 class="font-jakarta text-[48px] leading-[1.2] tracking-[-0.02em] font-extrabold text-[#181c20]">#{{ $order->queue_number }}</h3>
                                </div>
                                <div class="text-right">
                                    @if($order->type === 'dine_in')
                                        <span class="block font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold text-[#5e3f3b] mb-1">MEJA</span>
                                        <span class="font-jakarta text-[20px] leading-[28px] font-semibold text-[#bc000a] bg-[#e61919]/20 px-3 py-1 rounded-lg">M-{{ $order->table_number ?: '?' }}</span>
                                    @else
                                        <span class="block font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold text-[#5e3f3b] mb-1">BUNGKUS</span>
                                        <span class="font-jakarta text-[20px] leading-[28px] font-semibold text-[#181c20] bg-[#e5e8ee] px-3 py-1 rounded-lg">TA</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Cooking status --}}
                            <div class="flex items-center gap-2 text-[#785900] font-inter text-[14px] leading-[20px] bg-[#fdc003]/10 p-2 rounded-lg border border-[#fdc003]/20">
                                <span class="material-symbols-outlined text-[18px] animate-pulse">outdoor_grill</span>
                                <span>Sedang dimasak</span>
                            </div>

                            {{-- Items --}}
                            <div class="rounded-lg bg-[#ebeef3] p-2.5">
                                @foreach($order->details->take(3) as $detail)
                                    <div class="flex items-center justify-between py-0.5">
                                        <span class="truncate pr-2 font-inter text-xs text-[#181c20]">{{ $detail->menu_item_name }}</span>
                                        <span class="shrink-0 rounded bg-[#e0e3e8] px-1.5 py-0.5 font-inter text-xs font-bold text-[#5e3f3b]">×{{ $detail->quantity }}</span>
                                    </div>
                                @endforeach
                                @if($order->details->count() > 3)
                                    <p class="text-center font-inter text-xs text-[#936e69]">+{{ $order->details->count() - 3 }} lagi</p>
                                @endif
                            </div>

                            {{-- Total --}}
                            <div class="border-t border-[#e8bcb6] pt-2 text-right">
                                <p class="font-jakarta text-sm font-bold text-[#181c20]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>

                            {{-- Detail button --}}
                            <div class="mt-2 z-10">
                                <button
                                    wire:click="openDetailModal({{ $order->id }})"
                                    class="w-full bg-[#f7f9ff] border-2 border-[#fdc003] text-[#181c20] font-jakarta text-[16px] leading-[24px] font-medium py-2 rounded-lg hover:bg-[#fdc003] hover:text-[#6c5000] transition-colors flex justify-center items-center gap-2"
                                >
                                    <span class="material-symbols-outlined">receipt_long</span>
                                    Detail Pesanan
                                </button>
                            </div>
                        </article>
                    @endforeach
                @endif
            </section>
        </div>
    </main>

    {{-- ═══════════════════════════════════════════════════════════════════════
         BOTTOM NAV BAR (Mobile Only)
    ═══════════════════════════════════════════════════════════════════════ --}}
    <nav class="bg-[#ebeef3] border-t border-[#e8bcb6] shadow-lg fixed bottom-0 left-0 w-full z-50 flex justify-around items-center h-16 px-4 md:hidden">
        <button class="flex flex-col items-center justify-center bg-[#e61919] text-white rounded-xl px-4 py-1 scale-90 transition-transform">
            <span class="material-symbols-outlined">list_alt</span>
            <span class="font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold">Antrian</span>
        </button>
        <button class="flex flex-col items-center justify-center text-[#5e3f3b] px-4 py-1 hover:bg-[#e0e3e8] rounded-xl transition-colors">
            <span class="material-symbols-outlined">outdoor_grill</span>
            <span class="font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold">Proses</span>
        </button>
        <button class="flex flex-col items-center justify-center text-[#5e3f3b] px-4 py-1 hover:bg-[#e0e3e8] rounded-xl transition-colors">
            <span class="material-symbols-outlined">history</span>
            <span class="font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold">Riwayat</span>
        </button>
        <button class="flex flex-col items-center justify-center text-[#5e3f3b] px-4 py-1 hover:bg-[#e0e3e8] rounded-xl transition-colors">
            <span class="material-symbols-outlined">restaurant_menu</span>
            <span class="font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold">Menu</span>
        </button>
    </nav>


    {{-- ═══════════════════════════════════════════════════════════════════════
         MODAL 1 — DETAIL ORDER
    ═══════════════════════════════════════════════════════════════════════ --}}
    <div
        x-data="{ open: @entangle('showDetailModal') }"
        x-show="open"
        x-on:keydown.escape.window="$wire.closeDetailModal()"
        class="fixed inset-0 z-40 flex items-center justify-center p-4"
        style="display: none;"
    >
        {{-- Backdrop with blur --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-[#181c20]/40 backdrop-blur-sm"
            x-on:click="$wire.closeDetailModal()"
        ></div>

        {{-- Panel --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative z-50 w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-2xl flex flex-col max-h-[90vh]"
        >
            @if($this->selectedOrder)
                {{-- Modal Header --}}
                <div class="px-6 py-4 border-b border-[#ebeef3] flex items-center justify-between bg-[#f7f9ff] shrink-0">
                    <div class="flex items-center gap-4">
                        {{-- Order number big --}}
                        <span class="font-jakarta text-[48px] leading-[1.2] tracking-[-0.02em] font-extrabold text-[#bc000a]">#{{ $this->selectedOrder->queue_number }}</span>
                        <div class="flex flex-col">
                            <span class="font-jakarta text-[20px] leading-[28px] font-semibold text-[#181c20]">Detail Pesanan</span>
                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                {{-- Member badge --}}
                                @if($this->selectedOrder->member)
                                    <span class="bg-[#fdc003]/20 text-[#6c5000] px-3 py-1 rounded-full font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">person</span>
                                        {{ $this->selectedOrder->member->name }} - {{ number_format($this->selectedOrder->member->points) }} poin
                                    </span>
                                @else
                                    <span class="bg-[#e0e3e8] text-[#5e3f3b] px-3 py-1 rounded-full font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold">
                                        Umum
                                    </span>
                                @endif
                                {{-- Order type badge --}}
                                <span class="bg-[#e0e3e8] text-[#5e3f3b] px-3 py-1 rounded-full font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold">
                                    {{ $this->selectedOrder->type === 'dine_in'
                                        ? 'Dine In' . ($this->selectedOrder->table_number ? ' · Meja '.$this->selectedOrder->table_number : '')
                                        : 'Take Away' }}
                                </span>
                                {{-- Payment method badge --}}
                                @if($this->selectedOrder->payment_method === 'qris')
                                    <span class="bg-[#fdc003]/20 text-[#5b4300] px-3 py-1 rounded-full font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold">QRIS</span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold">Tunai</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- Close button --}}
                    <button
                        wire:click="closeDetailModal"
                        class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-[#e0e3e8] transition-colors text-[#5e3f3b] shrink-0"
                    >
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                {{-- Modal Body (Scrollable) --}}
                <div class="px-6 py-4 overflow-y-auto flex-1 flex flex-col gap-4">

                    {{-- Order Items List --}}
                    <div class="flex flex-col gap-4">
                        @foreach($this->selectedOrder->details as $detail)
                            <div class="flex items-start justify-between pb-4 border-b border-dashed border-[#ebeef3]">
                                <div class="flex items-start gap-4">
                                    {{-- Qty box --}}
                                    <div class="w-8 h-8 rounded bg-[#e0e3e8] flex items-center justify-center text-[#5e3f3b] font-jakarta text-[20px] leading-[28px] font-semibold shrink-0">
                                        {{ $detail->quantity }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-jakarta text-[16px] leading-[24px] font-medium text-[#181c20]">{{ $detail->menu_item_name }}</span>
                                        @if($detail->notes)
                                            <span class="font-inter text-[14px] leading-[20px] text-[#bc000a] mt-1">Catatan: {{ $detail->notes }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right shrink-0 ml-4">
                                    <span class="font-jakarta text-[16px] leading-[24px] font-medium text-[#181c20]">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Order notes --}}
                    @if($this->selectedOrder->notes)
                        <div class="rounded-lg border border-[#ffdf9e] bg-[#ffdf9e]/20 px-4 py-2.5">
                            <p class="font-inter text-[14px] leading-[20px] font-medium text-[#5b4300]">📝 Catatan Pesanan: {{ $this->selectedOrder->notes }}</p>
                        </div>
                    @endif

                    {{-- Calculation Section --}}
                    <div class="flex flex-col gap-2 bg-[#f7f9ff] p-4 rounded-lg border border-[#ebeef3]">
                        <div class="flex justify-between items-center">
                            <span class="font-inter text-[14px] leading-[20px] text-[#5e3f3b]">Subtotal</span>
                            <span class="font-inter text-[14px] leading-[20px] text-[#181c20]">Rp {{ number_format($this->selectedOrder->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($this->selectedOrder->discount_amount > 0)
                            <div class="flex justify-between items-center text-emerald-600">
                                <span class="font-inter text-[14px] leading-[20px]">🎟 Diskon Voucher</span>
                                <span class="font-inter text-[14px] leading-[20px]">- Rp {{ number_format($this->selectedOrder->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if((int)$this->selectedOrder->points_redeemed > 0)
                            <div class="flex justify-between items-center text-emerald-600">
                                <span class="font-inter text-[14px] leading-[20px]">Diskon Poin (-{{ $this->selectedOrder->points_redeemed }} pts)</span>
                                <span class="font-inter text-[14px] leading-[20px]">- Rp {{ number_format($this->selectedOrder->points_redeemed_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="border-t border-[#ebeef3] my-2"></div>
                        <div class="flex justify-between items-center">
                            <span class="font-jakarta text-[24px] leading-[32px] font-bold text-[#181c20]">Total Bayar</span>
                            <span class="font-jakarta text-[24px] leading-[32px] font-bold text-[#bc000a]">Rp {{ number_format($this->selectedOrder->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 border-t border-[#ebeef3] bg-[#f7f9ff] shrink-0 flex gap-4 justify-end items-center">
                    <button
                        wire:click="closeDetailModal"
                        class="px-6 py-3 rounded-lg border border-[#e8bcb6] text-[#5e3f3b] font-jakarta text-[16px] leading-[24px] font-medium hover:bg-[#e0e3e8] transition-colors"
                    >
                        Tutup
                    </button>
                    @if($this->selectedOrder->isPending())
                        <button
                            wire:click="openConfirmModal({{ $this->selectedOrder->id }})"
                            class="px-8 py-3 rounded-lg bg-emerald-600 text-white font-jakarta text-[16px] leading-[24px] font-medium hover:bg-emerald-700 transition-colors shadow-md flex items-center gap-2"
                        >
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            Konfirmasi Lunas
                        </button>
                    @endif
                </div>
            @else
                <div class="flex items-center justify-center p-16">
                    <svg class="h-8 w-8 animate-spin text-[#e0e3e8]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                </div>
            @endif
        </div>
    </div>



    {{-- ═══════════════════════════════════════════════════════════════════════
         MODAL 2 — KONFIRMASI PEMBAYARAN (Detail + Confirm)
    ═══════════════════════════════════════════════════════════════════════ --}}
    <div
        x-data
        x-show="$wire.confirmingOrderId !== null"
        x-on:keydown.escape.window="$wire.set('confirmingOrderId', null)"
        class="fixed inset-0 z-40 flex items-center justify-center p-4"
        style="display: none;"
    >
        {{-- Backdrop with blur --}}
        <div
            x-show="$wire.confirmingOrderId !== null"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-[#181c20]/40 backdrop-blur-sm"
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
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative z-50 w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-2xl flex flex-col max-h-[90vh]"
                >
                    {{-- Modal Header --}}
                    <div class="px-6 py-4 border-b border-[#ebeef3] flex items-center justify-between bg-[#f7f9ff] shrink-0">
                        <div class="flex items-center gap-4">
                            <span class="font-jakarta text-[48px] leading-[1.2] tracking-[-0.02em] font-extrabold text-[#bc000a]">#{{ $confirmOrder->queue_number }}</span>
                            <div class="flex flex-col">
                                <span class="font-jakarta text-[20px] leading-[28px] font-semibold text-[#181c20]">Detail Pesanan</span>
                                <div class="flex items-center gap-2 mt-1">
                                    {{-- Member badge --}}
                                    @if($confirmOrder->member)
                                        <span class="bg-[#fdc003]/20 text-[#6c5000] px-3 py-1 rounded-full font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">person</span>
                                            {{ $confirmOrder->member->name }} - {{ number_format($confirmOrder->member->points) }} poin
                                        </span>
                                    @else
                                        <span class="bg-[#e0e3e8] text-[#5e3f3b] px-3 py-1 rounded-full font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold">
                                            Umum
                                        </span>
                                    @endif
                                    {{-- Order type badge --}}
                                    <span class="bg-[#e0e3e8] text-[#5e3f3b] px-3 py-1 rounded-full font-inter text-[12px] leading-[16px] tracking-[0.05em] font-bold">
                                        {{ $confirmOrder->type === 'dine_in' ? 'Dine In' : 'Take Away' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button
                            wire:click="$set('confirmingOrderId', null)"
                            class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-[#e0e3e8] transition-colors text-[#5e3f3b]"
                        >
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    {{-- Modal Body (Scrollable) --}}
                    <div class="px-6 py-4 overflow-y-auto flex-1 flex flex-col gap-4">
                        {{-- Order Items List --}}
                        <div class="flex flex-col gap-4">
                            @foreach($confirmOrder->details as $detail)
                                <div class="flex items-start justify-between pb-4 border-b border-dashed border-[#ebeef3]">
                                    <div class="flex items-start gap-4">
                                        <div class="w-8 h-8 rounded bg-[#e0e3e8] flex items-center justify-center text-[#5e3f3b] font-jakarta text-[20px] leading-[28px] font-semibold shrink-0">
                                            {{ $detail->quantity }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-jakarta text-[16px] leading-[24px] font-medium text-[#181c20]">{{ $detail->menu_item_name }}</span>
                                            @if($detail->notes)
                                                <span class="font-inter text-[14px] leading-[20px] text-[#bc000a] mt-1">Catatan: {{ $detail->notes }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="font-jakarta text-[16px] leading-[24px] font-medium text-[#181c20]">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Calculation Section --}}
                        <div class="mt-4 flex flex-col gap-2 bg-[#f7f9ff] p-4 rounded-lg border border-[#ebeef3]">
                            <div class="flex justify-between items-center">
                                <span class="font-inter text-[14px] leading-[20px] text-[#5e3f3b]">Subtotal</span>
                                <span class="font-inter text-[14px] leading-[20px] text-[#181c20]">Rp {{ number_format($confirmOrder->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($confirmOrder->discount_amount > 0)
                                <div class="flex justify-between items-center text-emerald-600">
                                    <span class="font-inter text-[14px] leading-[20px]">🎟 Diskon Voucher</span>
                                    <span class="font-inter text-[14px] leading-[20px]">- Rp {{ number_format($confirmOrder->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if((int)$confirmOrder->points_redeemed > 0)
                                <div class="flex justify-between items-center text-emerald-600">
                                    <span class="font-inter text-[14px] leading-[20px]">Diskon Poin (-{{ $confirmOrder->points_redeemed }} pts)</span>
                                    <span class="font-inter text-[14px] leading-[20px]">- Rp {{ number_format($confirmOrder->points_redeemed_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="border-t border-[#ebeef3] my-2"></div>
                            <div class="flex justify-between items-center">
                                <span class="font-jakarta text-[24px] leading-[32px] font-bold text-[#181c20]">Total Bayar</span>
                                <span class="font-jakarta text-[24px] leading-[32px] font-bold text-[#bc000a]">Rp {{ number_format($confirmOrder->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 border-t border-[#ebeef3] bg-[#f7f9ff] shrink-0 flex gap-4 justify-end items-center">
                        <button
                            wire:click="$set('confirmingOrderId', null)"
                            class="px-6 py-3 rounded-lg border border-[#e8bcb6] text-[#5e3f3b] font-jakarta text-[16px] leading-[24px] font-medium hover:bg-[#e0e3e8] transition-colors"
                        >
                            Tutup
                        </button>
                        <button
                            wire:click="confirmPayment"
                            wire:loading.attr="disabled"
                            wire:target="confirmPayment"
                            class="px-8 py-3 rounded-lg bg-emerald-600 text-white font-jakarta text-[16px] leading-[24px] font-medium hover:bg-emerald-700 transition-colors shadow-md flex items-center gap-2 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <span wire:loading.remove wire:target="confirmPayment" class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                Konfirmasi Lunas
                            </span>
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
            class="relative z-50 w-full max-w-md overflow-hidden rounded-2xl bg-[#f7f9ff] shadow-2xl border border-[#e8bcb6]"
        >
            {{-- Header --}}
            <div class="flex items-center gap-4 border-b border-[#e0e3e8] px-6 py-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#ffdad6] text-2xl">⚠️</div>
                <div>
                    <h3 class="font-jakarta text-xl font-bold text-[#181c20]">Batalkan Pesanan</h3>
                    <p class="font-inter text-sm text-[#936e69]">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5">
                <label class="block">
                    <span class="mb-2 block font-jakarta text-sm font-bold text-[#5e3f3b]">
                        Alasan Pembatalan
                        <span class="ml-0.5 text-[#ba1a1a]">*</span>
                    </span>
                    <textarea
                        wire:model="cancelReason"
                        rows="4"
                        placeholder="Contoh: Pelanggan membatalkan pesanan, stok habis, dll. (min. 3 karakter)"
                        class="w-full resize-none rounded-xl border border-[#e8bcb6] bg-white px-4 py-3 font-inter text-sm text-[#181c20] placeholder-[#936e69] outline-none transition focus:border-[#bc000a] focus:ring-2 focus:ring-[#bc000a]/20"
                    ></textarea>
                    @error('cancelReason')
                        <p class="mt-1.5 flex items-center gap-1 font-inter text-xs font-medium text-[#ba1a1a]">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </label>
            </div>

            {{-- Footer --}}
            <div class="flex gap-3 border-t border-[#e0e3e8] px-6 py-4">
                <button
                    wire:click="closeCancelModal"
                    class="flex-1 rounded-xl border border-[#936e69] bg-[#f7f9ff] px-4 py-3 font-inter text-sm font-semibold text-[#5e3f3b] transition hover:bg-[#ebeef3]"
                >
                    Tutup
                </button>
                <button
                    wire:click="cancelOrder"
                    wire:loading.attr="disabled"
                    wire:target="cancelOrder"
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-[#ba1a1a] px-4 py-3 font-jakarta text-sm font-bold text-white transition hover:bg-[#93000a] disabled:cursor-not-allowed disabled:opacity-60"
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
