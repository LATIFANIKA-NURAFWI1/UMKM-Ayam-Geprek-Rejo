<div class="min-h-screen bg-gray-50 flex flex-col">

    {{-- =====================================================================
         HERO: SUCCESS ANIMATION
         ===================================================================== --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-lg mx-auto px-4 pt-10 pb-8 flex flex-col items-center text-center">

            {{-- Green checkmark circle --}}
            <div class="relative mb-5">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center animate-[bounce_0.6s_ease-out_1]">
                    <svg class="w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                {{-- Pulse ring --}}
                <span class="absolute inset-0 rounded-full bg-green-300 opacity-30 animate-ping"></span>
            </div>

            <h1 class="font-jakarta text-2xl font-black text-gray-900">Pesanan Berhasil! 🎉</h1>
            <p class="font-inter text-gray-500 mt-1 text-sm">Pesanan Anda sedang diproses oleh dapur</p>

        </div>
    </div>

    {{-- =====================================================================
         QUEUE NUMBER — CENTER STAGE
         ===================================================================== --}}
    <div class="bg-gradient-to-br from-[#e61919] to-[#bc000a] shadow-lg">
        <div class="max-w-lg mx-auto px-6 py-8 text-center">
            <p class="font-inter text-[#e8bcb6] text-sm font-semibold uppercase tracking-widest mb-1">Nomor Antrean Anda</p>

            <p class="font-jakarta text-9xl font-black text-white leading-none drop-shadow-md">
                {{ $order->queue_number }}
            </p>
        </div>
    </div>

    {{-- =====================================================================
         ORDER DETAILS
         ===================================================================== --}}
    <div class="flex-1 max-w-lg mx-auto w-full px-4 py-5 space-y-4">

        {{-- Order header info --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-white rounded-2xl p-3.5 text-center shadow-sm">
                <p class="font-inter text-xs text-gray-400 mb-1">Pesanan</p>
                <p class="font-jakarta text-xs font-bold text-gray-900 truncate">{{ $order->order_number }}</p>
            </div>
            <div class="bg-white rounded-2xl p-3.5 text-center shadow-sm">
                <p class="font-inter text-xs text-gray-400 mb-1">Jenis</p>
                <p class="font-jakarta text-xs font-bold text-gray-900">
                    {{ $order->type === 'dine_in' ? '🍽️ Di Sini' : '🥡 Bawa Pulang' }}
                </p>
            </div>
            <div class="bg-white rounded-2xl p-3.5 text-center shadow-sm">
                <p class="font-inter text-xs text-gray-400 mb-1">Bayar</p>
                <p class="font-jakarta text-xs font-bold text-gray-900 uppercase">
                    {{ $order->payment_method === 'qris' ? '📱 QRIS' : '💵 Tunai' }}
                </p>
            </div>
        </div>

        {{-- Customer name + table --}}
        <div class="bg-white rounded-2xl shadow-sm px-4 py-3.5 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#e61919] to-[#bc000a] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr($customerName, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900 truncate">{{ $customerName }}</p>
                @if($order->table_number)
                    <p class="text-xs text-gray-500">🪑 Meja {{ $order->table_number }}</p>
                @endif
            </div>
            @if($order->member)
                <span class="text-xs bg-[#ffdad5] text-[#bc000a] px-2.5 py-1 rounded-full font-semibold flex-shrink-0">
                    ⭐ Member
                </span>
            @endif
        </div>

        {{-- Order items list --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#bc000a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h2 class="font-jakarta font-semibold text-gray-900 text-sm">Detail Pesanan</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($this->orderDetails as $detail)
                    <div class="flex items-center gap-3 px-4 py-3">
                        <div class="w-9 h-9 bg-[#fff8f7] rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-lg">🍗</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $detail->menu_item_name }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $detail->quantity }} × Rp {{ number_format($detail->unit_price, 0, ',', '.') }}
                            </p>
                        </div>
                        <p class="text-sm font-semibold text-gray-700 flex-shrink-0">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Payment summary --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-4 py-4 space-y-2.5">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium text-gray-900">
                        Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                    </span>
                </div>

                @if((float)$order->discount_amount > 0)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-green-600">Diskon Voucher</span>
                        <span class="font-semibold text-green-600">
                            −Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                        </span>
                    </div>
                @endif

                @if((float)$order->points_redeemed_amount > 0)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-blue-600">Diskon Poin ({{ $order->points_redeemed }} poin)</span>
                        <span class="font-semibold text-blue-600">
                            −Rp {{ number_format($order->points_redeemed_amount, 0, ',', '.') }}
                        </span>
                    </div>
                @endif

                <div class="pt-2.5 border-t border-gray-100 flex items-center justify-between">
                    <span class="font-jakarta font-bold text-gray-900">Total Dibayar</span>
                    <span class="font-jakarta text-2xl font-black text-[#bc000a]">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Points earned notification (if member) --}}
        @if($order->member && $order->points_earned > 0)
            <div class="bg-[#fff8f7] border border-amber-200 rounded-2xl px-4 py-3.5 flex items-center gap-3">
                <span class="text-2xl flex-shrink-0">⭐</span>
                <div>
                    <p class="text-sm font-semibold text-amber-800">
                        +{{ number_format($order->points_earned, 0, ',', '.') }} poin diperoleh!
                    </p>
                    <p class="text-xs text-amber-600 mt-0.5">
                        Poin akan ditambahkan setelah pembayaran dikonfirmasi
                    </p>
                </div>
            </div>
        @endif

        {{-- Notes --}}
        @if($order->notes)
            <div class="bg-white rounded-2xl shadow-sm px-4 py-3.5">
                <p class="text-xs font-medium text-gray-500 mb-1">Catatan Pesanan</p>
                <p class="text-sm text-gray-700">{{ $order->notes }}</p>
            </div>
        @endif

        {{-- Pesan Lagi button --}}
        <div class="pt-2 pb-6">
            <a href="{{ route('order.menu') }}" wire:navigate
               class="w-full flex items-center justify-center gap-2 py-4 bg-[#bc000a] text-white font-bold text-base rounded-2xl shadow-lg hover:bg-[#c0000b] active:scale-[0.98] transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Pesan Lagi
            </a>
        </div>

    </div>

</div>

