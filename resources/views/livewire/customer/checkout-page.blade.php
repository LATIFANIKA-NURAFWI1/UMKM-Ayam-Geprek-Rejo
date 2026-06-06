<div class="min-h-screen bg-gray-50">

    {{-- =====================================================================
         STICKY HEADER
         ===================================================================== --}}
    <div class="sticky top-0 z-20 bg-white border-b border-gray-200 shadow-sm">
        <div class="flex items-center gap-3 px-4 py-3 max-w-lg mx-auto">
            <a href="{{ route('order.menu') }}" wire:navigate
               class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 transition text-gray-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-lg font-bold text-gray-900 leading-tight">Pesanan Saya</h1>
            </div>
            @if(!empty($cart))
                <span class="text-sm font-medium text-orange-600 bg-orange-50 px-2.5 py-1 rounded-full flex-shrink-0">
                    {{ count($cart) }} item
                </span>
            @endif
        </div>
    </div>

    {{-- =====================================================================
         EMPTY CART STATE
         ===================================================================== --}}
    @if(empty($cart))
        <div class="flex flex-col items-center justify-center px-6 py-24 text-center max-w-lg mx-auto">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <p class="text-lg font-semibold text-gray-700">Keranjang masih kosong</p>
            <p class="text-sm text-gray-400 mt-1 mb-6">Yuk pilih menu lezat kami!</p>
            <a href="{{ route('order.menu') }}" wire:navigate
               class="px-8 py-3 bg-orange-500 text-white font-semibold rounded-2xl hover:bg-orange-600 transition active:scale-95">
                Pilih Menu
            </a>
        </div>

    @else
        <div class="max-w-lg mx-auto px-4 pt-4 pb-36 space-y-4">

            {{-- ==============================================================
                 SECTION 1: CART SUMMARY
                 ============================================================== --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                    <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h2 class="font-semibold text-gray-900">Ringkasan Pesanan</h2>
                </div>

                <div class="divide-y divide-gray-50">
                    @foreach($cart as $itemId => $item)
                        <div class="flex items-center gap-3 px-4 py-3">
                            {{-- Item image --}}
                            @if(!empty($item['image']))
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($item['image']) }}"
                                     alt="{{ $item['name'] }}"
                                     class="w-14 h-14 rounded-xl object-cover flex-shrink-0">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                                    <span class="text-2xl">🍗</span>
                                </div>
                            @endif

                            {{-- Item info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 text-sm leading-tight truncate">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }} / pcs
                                </p>
                            </div>

                            {{-- Qty + subtotal --}}
                            <div class="text-right flex-shrink-0">
                                <p class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full inline-block">
                                    ×{{ $item['quantity'] }}
                                </p>
                                <p class="text-sm font-bold text-orange-600 mt-1">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Cart subtotal --}}
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-t border-gray-100">
                    <span class="text-sm text-gray-600 font-medium">Subtotal ({{ count($cart) }} item)</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- ==============================================================
                 SECTION 2: ORDER INFO
                 ============================================================== --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                    <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <h2 class="font-semibold text-gray-900">Informasi Pesanan</h2>
                </div>
                <div class="px-4 py-4 space-y-4">

                    {{-- Customer Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Pelanggan
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="customerName"
                            placeholder="Contoh: Budi Santoso"
                            autocomplete="name"
                            class="w-full px-3.5 py-2.5 border rounded-xl text-sm transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent
                                   @error('customerName') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                        @error('customerName')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Table number badge (if from QR scan) --}}
                    @if($tableNumber)
                        <div class="flex items-center gap-2 px-3.5 py-2.5 bg-orange-50 border border-orange-200 rounded-xl">
                            <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h18M3 14h18M8 6h.01M16 6h.01M8 18h.01M16 18h.01"/>
                            </svg>
                            <span class="text-sm font-medium text-orange-700">Meja: <strong>{{ $tableNumber }}</strong></span>
                        </div>
                    @endif

                    {{-- Order Type Toggle --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pesanan</label>
                        <div class="grid grid-cols-2 gap-1 p-1 bg-gray-100 rounded-xl">
                            <button type="button" wire:click="$set('orderType', 'dine_in')"
                                class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg text-sm font-medium transition-all
                                       {{ $orderType === 'dine_in'
                                          ? 'bg-white text-orange-600 shadow-sm'
                                          : 'text-gray-500 hover:text-gray-700' }}">
                                <span>🍽️</span>
                                <span>Makan di Sini</span>
                            </button>
                            <button type="button" wire:click="$set('orderType', 'takeaway')"
                                class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg text-sm font-medium transition-all
                                       {{ $orderType === 'takeaway'
                                          ? 'bg-white text-orange-600 shadow-sm'
                                          : 'text-gray-500 hover:text-gray-700' }}">
                                <span>🥡</span>
                                <span>Bawa Pulang</span>
                            </button>
                        </div>
                    </div>

                    {{-- Order Notes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Catatan
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea
                            wire:model="orderNotes"
                            placeholder="Misal: tidak pedas, extra nasi, sambal terpisah..."
                            rows="2"
                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm resize-none transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"></textarea>
                    </div>

                </div>
            </div>

            {{-- ==============================================================
                 SECTION 3: MEMBER (collapsible)
                 ============================================================== --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

                @if($loggedInMemberId)
                    {{-- Member is logged in — show info + points slider --}}
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="text-base">⭐</span>
                            <h2 class="font-semibold text-gray-900">Program Member</h2>
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">
                                ✓ Login
                            </span>
                        </div>
                        <button type="button" wire:click="logoutMember"
                            wire:confirm="Keluar dari akun member?"
                            class="text-xs text-red-500 px-3 py-1.5 border border-red-200 rounded-lg hover:bg-red-50 transition font-medium">
                            Keluar
                        </button>
                    </div>

                    <div class="px-4 py-4 space-y-3">
                        {{-- Member info --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($loggedInMemberName, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ $loggedInMemberName }}</p>
                                <p class="text-xs text-gray-500">
                                    💎 {{ number_format($memberPoints, 0, ',', '.') }} poin tersedia
                                </p>
                            </div>
                        </div>

                        {{-- Points redemption slider --}}
                        @if($memberPoints > 0)
                            @php
                                $maxRedeem = min($memberPoints, (int) $this->subtotal);
                            @endphp
                            <div class="bg-orange-50 rounded-xl p-3.5 space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600 font-medium">Tukar Poin</span>
                                    @if($pointsToRedeem > 0)
                                        <span class="font-semibold text-orange-600">
                                            {{ number_format($pointsToRedeem, 0, ',', '.') }} poin
                                            = <span class="text-green-600">−Rp {{ number_format($this->pointsDiscountAmount, 0, ',', '.') }}</span>
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">Geser untuk tukar poin</span>
                                    @endif
                                </div>
                                <input
                                    type="range"
                                    wire:model.live="pointsToRedeem"
                                    min="0"
                                    max="{{ $maxRedeem }}"
                                    step="1"
                                    class="w-full h-2 accent-orange-500 cursor-pointer">
                                <div class="flex justify-between text-xs text-gray-400">
                                    <span>0 poin</span>
                                    <span>{{ number_format($maxRedeem, 0, ',', '.') }} poin maks</span>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic">Saldo poin Anda 0. Belanja lebih banyak untuk kumpulkan poin!</p>
                        @endif
                    </div>

                @else
                    {{-- Not logged in — show toggle button for login form --}}
                    <button type="button" wire:click="$toggle('showMemberForm')"
                        class="w-full flex items-center justify-between px-4 py-3.5 text-left hover:bg-gray-50 transition">
                        <div class="flex items-center gap-2">
                            <span class="text-base">⭐</span>
                            <div>
                                <span class="font-semibold text-gray-900 text-sm block">Program Member</span>
                                <span class="text-xs text-gray-400">Login untuk tukar poin & diskon eksklusif</span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transition-transform {{ $showMemberForm ? 'rotate-180' : '' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    @if($showMemberForm)
                        <div class="border-t border-gray-100 px-4 py-4 space-y-3">
                            <p class="text-sm text-gray-500">Masukkan nomor HP dan PIN member Anda.</p>

                            {{-- Error message --}}
                            @if($memberLoginError)
                                <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 text-sm px-3.5 py-2.5 rounded-xl">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span>{{ $memberLoginError }}</span>
                                </div>
                            @endif

                            <div>
                                <input
                                    type="tel"
                                    wire:model="memberPhone"
                                    placeholder="Nomor HP (08xx...)"
                                    autocomplete="tel"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                            </div>
                            <div>
                                <input
                                    type="password"
                                    wire:model="memberPin"
                                    placeholder="PIN (6 digit)"
                                    maxlength="6"
                                    inputmode="numeric"
                                    wire:keydown.enter="loginMember"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                            </div>
                            <button type="button" wire:click="loginMember"
                                wire:loading.attr="disabled" wire:loading.class="opacity-60" wire:target="loginMember"
                                class="w-full py-2.5 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 transition active:scale-95">
                                <span wire:loading.remove wire:target="loginMember">Masuk Member</span>
                                <span wire:loading wire:target="loginMember">Memverifikasi...</span>
                            </button>
                        </div>
                    @endif
                @endif
            </div>

            {{-- ==============================================================
                 SECTION 4: VOUCHER
                 ============================================================== --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                    <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    <h2 class="font-semibold text-gray-900">Voucher</h2>
                </div>
                <div class="px-4 py-4">

                    @if($voucherApplied)
                        {{-- Applied voucher --}}
                        <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-green-800 tracking-wider">{{ strtoupper($voucherCode) }}</p>
                                    <p class="text-xs text-green-600">
                                        Hemat Rp {{ number_format($voucherDiscount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            <button type="button" wire:click="removeVoucher"
                                class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                    @else
                        {{-- Voucher input --}}
                        <div class="flex gap-2">
                            <input
                                type="text"
                                wire:model="voucherCode"
                                placeholder="Kode voucher"
                                class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                                wire:keydown.enter="applyVoucher">
                            <button type="button" wire:click="applyVoucher"
                                wire:loading.attr="disabled" wire:target="applyVoucher"
                                class="px-4 py-2.5 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 transition whitespace-nowrap disabled:opacity-60">
                                <span wire:loading.remove wire:target="applyVoucher">Pakai</span>
                                <span wire:loading wire:target="applyVoucher">...</span>
                            </button>
                        </div>

                        @if($voucherError)
                            <p class="mt-2 text-xs text-red-500 flex items-start gap-1.5">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $voucherError }}
                            </p>
                        @endif
                    @endif
                </div>
            </div>

            {{-- ==============================================================
                 SECTION 5: PAYMENT METHOD
                 ============================================================== --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                    <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <h2 class="font-semibold text-gray-900">Metode Pembayaran</h2>
                </div>
                <div class="px-4 py-4">

                    @error('paymentMethod')
                        <p class="mb-3 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    <div class="grid grid-cols-2 gap-3">
                        {{-- QRIS --}}
                        <button type="button" wire:click="$set('paymentMethod', 'qris')"
                            class="relative flex flex-col items-center gap-2.5 p-4 border-2 rounded-2xl transition-all
                                   {{ $paymentMethod === 'qris'
                                      ? 'border-orange-500 bg-orange-50 shadow-sm'
                                      : 'border-gray-200 bg-white hover:border-gray-300' }}">
                            {{-- Selected checkmark --}}
                            @if($paymentMethod === 'qris')
                                <span class="absolute top-2 right-2 w-5 h-5 bg-orange-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                            @endif
                            {{-- QR icon --}}
                            <svg class="w-10 h-10 {{ $paymentMethod === 'qris' ? 'text-orange-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12v.01M12 3.01V3M4 20h4m-4 0V4m0 0h4m12 0h-4m4 0v4m0 12v-4M4 8h4V4M4 4h4m12 0h-4m4 0v4m-4 16v-4m0 4h-4"/>
                            </svg>
                            <div class="text-center">
                                <p class="font-bold text-sm {{ $paymentMethod === 'qris' ? 'text-orange-700' : 'text-gray-700' }}">QRIS</p>
                                <p class="text-xs text-gray-400 mt-0.5">Scan QR Code</p>
                            </div>
                        </button>

                        {{-- Cash --}}
                        <button type="button" wire:click="$set('paymentMethod', 'cash')"
                            class="relative flex flex-col items-center gap-2.5 p-4 border-2 rounded-2xl transition-all
                                   {{ $paymentMethod === 'cash'
                                      ? 'border-orange-500 bg-orange-50 shadow-sm'
                                      : 'border-gray-200 bg-white hover:border-gray-300' }}">
                            {{-- Selected checkmark --}}
                            @if($paymentMethod === 'cash')
                                <span class="absolute top-2 right-2 w-5 h-5 bg-orange-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                            @endif
                            {{-- Cash icon --}}
                            <svg class="w-10 h-10 {{ $paymentMethod === 'cash' ? 'text-orange-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <div class="text-center">
                                <p class="font-bold text-sm {{ $paymentMethod === 'cash' ? 'text-orange-700' : 'text-gray-700' }}">Tunai</p>
                                <p class="text-xs text-gray-400 mt-0.5">Bayar ke Kasir</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ==============================================================
                 SECTION 6: ORDER SUMMARY
                 ============================================================== --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                    <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                    <h2 class="font-semibold text-gray-900">Rincian Biaya</h2>
                </div>
                <div class="px-4 py-4 space-y-3">
                    {{-- Subtotal row --}}
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                    </div>

                    {{-- Voucher discount --}}
                    @if($voucherApplied && $voucherDiscount > 0)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-green-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Voucher ({{ strtoupper($voucherCode) }})
                            </span>
                            <span class="font-semibold text-green-600">
                                −Rp {{ number_format($voucherDiscount, 0, ',', '.') }}
                            </span>
                        </div>
                    @endif

                    {{-- Points discount --}}
                    @if($this->pointsDiscountAmount > 0)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-blue-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Poin ({{ number_format($pointsToRedeem, 0, ',', '.') }} poin)
                            </span>
                            <span class="font-semibold text-blue-600">
                                −Rp {{ number_format($this->pointsDiscountAmount, 0, ',', '.') }}
                            </span>
                        </div>
                    @endif

                    {{-- Total --}}
                    <div class="pt-3 border-t border-gray-200 flex items-center justify-between">
                        <span class="font-bold text-gray-900 text-base">Total Bayar</span>
                        <span class="text-2xl font-black text-orange-600">
                            Rp {{ number_format($this->totalAmount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ==============================================================
                 ERROR MESSAGE
                 ============================================================== --}}
            @if($orderError)
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-sm text-red-700 leading-relaxed">{{ $orderError }}</p>
                </div>
            @endif

        </div>{{-- end .pb-36 --}}

        {{-- ==================================================================
             FIXED BOTTOM: SUBMIT BUTTON
             ================================================================== --}}
        <div class="fixed bottom-0 inset-x-0 z-10 bg-white border-t border-gray-200 shadow-[0_-4px_16px_rgba(0,0,0,0.08)]">
            <div class="px-4 py-3 pb-safe max-w-lg mx-auto">
                <button
                    type="button"
                    wire:click="placeOrder"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60 cursor-not-allowed"
                    wire:target="placeOrder"
                    class="w-full flex items-center justify-center gap-2.5 py-4 bg-orange-500 text-white font-bold text-base rounded-2xl shadow-lg hover:bg-orange-600 active:scale-[0.98] transition-all disabled:opacity-60 disabled:cursor-not-allowed">

                    {{-- Default state --}}
                    <span wire:loading.remove wire:target="placeOrder" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Pesan Sekarang —
                        Rp {{ number_format($this->totalAmount, 0, ',', '.') }}
                    </span>

                    {{-- Loading state --}}
                    <span wire:loading wire:target="placeOrder" class="flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses pesanan...
                    </span>
                </button>
            </div>
        </div>

    @endif

</div>
