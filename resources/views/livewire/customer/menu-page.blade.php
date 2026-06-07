<div class="min-h-[max(884px,100dvh)] bg-[#f7f9ff] text-[#181c20] pb-36">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- TOP APP BAR                                               --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <header class="bg-white w-full top-0 sticky z-40 border-b border-[#e0e3e8] shadow-sm flex justify-between items-center px-6 py-2">
        {{-- Brand --}}
        <div class="flex flex-col">
            <span class="font-inter text-[10px] tracking-widest text-[#5e3f3b] uppercase font-bold leading-tight">SELF ORDER</span>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#bc000a]">restaurant_menu</span>
                <span class="font-jakarta text-[20px] leading-[28px] font-semibold text-[#bc000a]">Geprek Rejo</span>
            </div>
        </div>

        {{-- Right Controls: Member + Cart --}}
        <div class="flex items-center gap-3">
            {{-- Member Button --}}
            @if($loggedInMemberId)
                <button wire:click="$set('showMemberModal', true)"
                        class="font-inter flex items-center gap-1.5 rounded-full bg-[#fdc003] text-[#6c5000] px-3 py-1.5 text-[10px] font-bold shadow-sm transition active:scale-95 hover:opacity-90">
                    <span class="material-symbols-outlined text-[14px]">star</span>
                    <span>{{ explode(' ', $loggedInMemberName)[0] }} · {{ number_format($loggedInMemberPoints, 0, ',', '.') }} P</span>
                </button>
            @else
                <button wire:click="$set('showMemberModal', true)"
                        class="font-inter bg-[#bc000a] text-white py-1.5 px-3 rounded-full transition-all active:scale-95 flex items-center gap-1 shadow-sm text-[10px] font-bold tracking-wide hover:bg-[#c0000b]">
                    DAFTAR / LOGIN
                </button>
            @endif

            {{-- Cart Icon --}}
            <div wire:click="goToCheckout"
                 class="relative p-2 hover:bg-[#e5e8ee] rounded-full transition-colors cursor-pointer">
                <span class="material-symbols-outlined text-[28px]">shopping_cart</span>
                @if($this->cartCount > 0)
                    <span class="font-inter absolute top-0 right-0 bg-[#fdc003] text-[#6c5000] w-5 h-5 rounded-full text-[10px] flex items-center justify-center font-bold border-2 border-white">
                        {{ $this->cartCount }}
                    </span>
                @endif
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 pb-32">

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- SEARCH BAR                                                --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <section class="mt-8">
            <div class="relative group">
                <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-[#5e3f3b] group-focus-within:text-[#bc000a] transition-colors">search</span>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchQuery"
                    placeholder="Cari menu favoritmu..."
                    class="w-full font-jakarta bg-white border-none rounded-xl py-5 pl-14 pr-10 shadow-[0_10px_30px_-12px_rgba(0,0,0,0.08)] focus:ring-2 focus:ring-[#bc000a]/20 text-[16px] leading-[24px] font-medium placeholder:text-[#5e3f3b]/50 transition-all outline-none"
                >
                {{-- Clear search --}}
                @if($searchQuery)
                    <button
                        wire:click="$set('searchQuery', '')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full text-[#5e3f3b] transition hover:text-[#181c20]"
                        aria-label="Hapus pencarian"
                    >
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                @endif
                {{-- Loading spinner --}}
                <div wire:loading wire:target="searchQuery" class="absolute right-4 top-1/2 -translate-y-1/2">
                    <svg class="h-4 w-4 animate-spin text-[#bc000a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- CATEGORY TABS (sticky)                                    --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <section class="mt-2 overflow-x-auto hide-scrollbar -mx-6 px-6 py-4 flex flex-wrap justify-center gap-3 sticky top-[72px] bg-[#f7f9ff]/95 backdrop-blur-sm z-30">
            {{-- "Semua" tab --}}
            <button
                wire:click="$set('activeCategory', null)"
                @class([
                    'font-inter whitespace-nowrap px-6 py-2.5 rounded-full text-[12px] leading-[16px] tracking-[0.05em] font-bold transition-all',
                    'bg-[#bc000a] text-white shadow-lg' => $activeCategory === null,
                    'bg-white text-[#181c20] hover:bg-[#e5e8ee] border border-[#e0e3e8]' => $activeCategory !== null,
                ])
            >
                SEMUA
            </button>

            @foreach($this->categories as $cat)
                <button
                    wire:click="$set('activeCategory', {{ $cat->id }})"
                    @class([
                        'font-inter whitespace-nowrap px-6 py-2.5 rounded-full text-[12px] leading-[16px] tracking-[0.05em] font-bold transition-all',
                        'bg-[#bc000a] text-white shadow-lg' => $activeCategory === $cat->id,
                        'bg-white text-[#181c20] hover:bg-[#e5e8ee] border border-[#e0e3e8]' => $activeCategory !== $cat->id,
                    ])
                >
                    @if($cat->icon)<span>{{ $cat->icon }}</span> @endif{{ strtoupper($cat->name) }}
                </button>
            @endforeach
        </section>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- FLASH ERROR                                               --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        @if(session()->has('cart_error'))
            <div class="mb-4 flex items-center gap-2 rounded-xl border border-[#ffdad6] bg-[#ffdad6] px-4 py-3 text-sm text-[#93000a]">
                <span class="material-symbols-outlined text-[18px]">warning</span>
                {{ session('cart_error') }}
            </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- MENU GRID                                                  --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        @if($this->menuItems->isEmpty())

            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center pb-8 pt-16 text-center">
                <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-[#ebeef3]">
                    <span class="material-symbols-outlined text-5xl text-[#bc000a]">restaurant_menu</span>
                </div>
                <h3 class="text-base font-semibold text-[#181c20]">Menu tidak ditemukan</h3>
                <p class="mt-1 text-sm text-[#5e3f3b]">
                    @if($searchQuery)
                        Tidak ada menu untuk "<span class="font-medium text-[#181c20]">{{ $searchQuery }}</span>"
                    @else
                        Belum ada menu di kategori ini
                    @endif
                </p>
                @if($searchQuery || $activeCategory)
                    <button
                        wire:click="clearFilters"
                        class="mt-4 rounded-full bg-[#bc000a] text-white px-5 py-2 text-sm font-bold tracking-wide transition hover:bg-[#c0000b] active:scale-95"
                    >
                        Lihat semua menu
                    </button>
                @endif
            </div>

        @else

            {{-- 2–4 Column Menu Grid --}}
            <div
                wire:loading.class="opacity-60 pointer-events-none"
                wire:target="activeCategory, searchQuery"
                class="mt-2 grid grid-cols-2 lg:grid-cols-4 gap-4 transition-opacity duration-150"
            >
                @foreach($this->menuItems as $item)
                    @php $inCart = isset($this->cart[$item->id]); @endphp

                    {{-- Menu Card --}}
                    <div class="bg-white rounded-xl overflow-hidden shadow-[0_10px_30px_-12px_rgba(0,0,0,0.08)] border border-[#e0e3e8]/50 flex flex-col hover:-translate-y-1 transition-transform duration-300">

                        {{-- Image --}}
                        <div wire:click="showMenuDetail({{ $item->id }})" class="aspect-[4/3] w-full relative overflow-hidden cursor-pointer">
                            @if($item->image)
                                <img
                                    src="{{ Storage::url($item->image) }}?v={{ time() }}"
                                    alt="{{ $item->name }}"
                                    class="w-full h-full object-cover transition duration-300 hover:scale-105"
                                    loading="lazy"
                                />
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#e61919] to-[#bc000a]">
                                    <span class="text-5xl drop-shadow">🍗</span>
                                </div>
                            @endif

                            {{-- Qty badge (top-left) --}}
                            @if($inCart)
                                <div class="absolute left-2 top-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#fdc003] text-[#6c5000] px-1.5 text-[10px] font-bold shadow">
                                    {{ $this->cart[$item->id]['quantity'] }}
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-3 flex flex-col flex-grow">
                            <h3 wire:click="showMenuDetail({{ $item->id }})"
                                class="font-jakarta font-semibold text-sm text-[#181c20] mb-1 line-clamp-1 cursor-pointer">
                                {{ $item->name }}
                            </h3>
                            @if($item->category)
                                <p class="text-[#5e3f3b] text-[10px] leading-tight mb-2">{{ $item->category->name }}</p>
                            @endif

                            <div class="mt-auto flex justify-between items-center">
                                <span class="font-bold text-[#bc000a] text-sm">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </span>

                                {{-- Cart Controls --}}
                                @if(! $inCart)
                                    <button
                                        wire:click="addToCart({{ $item->id }})"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-60 scale-90"
                                        wire:target="addToCart({{ $item->id }})"
                                        class="w-8 h-8 rounded-lg bg-[#bc000a] text-white flex items-center justify-center hover:bg-[#c0000b] active:scale-90 transition-all shadow-md disabled:cursor-not-allowed"
                                        aria-label="Tambah {{ $item->name }} ke keranjang"
                                    >
                                        <span class="material-symbols-outlined text-sm">add</span>
                                    </button>
                                @else
                                    <div class="flex items-center gap-1.5 bg-[#ebeef3] rounded-lg p-1">
                                        <button
                                            wire:click="decreaseQty({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="decreaseQty({{ $item->id }})"
                                            class="w-7 h-7 rounded-md bg-white text-[#bc000a] flex items-center justify-center hover:bg-white shadow-sm transition-all disabled:opacity-60"
                                            aria-label="Kurangi {{ $item->name }}"
                                        >
                                            <span class="material-symbols-outlined text-[14px]">remove</span>
                                        </button>
                                        <span class="font-bold text-[#181c20] text-xs px-1.5 min-w-[20px] text-center">
                                            {{ $this->cart[$item->id]['quantity'] }}
                                        </span>
                                        <button
                                            wire:click="increaseQty({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="increaseQty({{ $item->id }})"
                                            class="w-7 h-7 rounded-md bg-[#bc000a] text-white flex items-center justify-center hover:bg-[#c0000b] shadow-sm transition-all disabled:opacity-60"
                                            aria-label="Tambah {{ $item->name }}"
                                        >
                                            <span class="material-symbols-outlined text-[14px]">add</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- / Menu Card --}}

                @endforeach
            </div>
            {{-- / Menu Grid --}}

        @endif
    </main>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- FIXED BOTTOM CART BAR                                     --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="fixed bottom-0 left-0 w-full z-50 p-6 pointer-events-none">
        @if($this->cartCount > 0)
            <div class="max-w-3xl mx-auto w-full pointer-events-auto">
                <button
                    wire:click="goToCheckout"
                    wire:loading.attr="disabled"
                    wire:target="goToCheckout"
                    class="group w-full bg-[#e61919] text-white rounded-xl shadow-[0_-4px_30px_rgba(230,25,25,0.4)] flex justify-between items-center px-6 py-4 cursor-pointer active:translate-y-0.5 transition-transform hover:opacity-95 disabled:opacity-70"
                >
                    <div class="flex items-center gap-4">
                        <div class="bg-white text-[#bc000a] w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                            {{ $this->cartCount }}
                        </div>
                        <span class="font-jakarta text-[16px] leading-[24px] font-bold tracking-wide">Lihat Keranjang</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-jakarta text-[16px] font-semibold">
                            Rp {{ number_format($this->cartTotal, 0, ',', '.') }}
                        </span>
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>
                </button>
            </div>
        @else
            <div class="max-w-3xl mx-auto w-full pointer-events-auto">
                <div class="w-full bg-[#e5e8ee] text-[#5e3f3b] rounded-xl flex justify-center items-center gap-2 px-6 py-4 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
                    <span class="font-semibold text-sm">Keranjang Kosong</span>
                </div>
            </div>
        @endif
    </div>
    {{-- / BOTTOM CART BAR --}}

    {{-- Background Decoration --}}
    <div class="fixed top-0 left-0 w-full h-full -z-10 opacity-30 pointer-events-none">
        <div class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] bg-[#bc000a]/5 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[50%] h-[50%] bg-[#fdc003]/5 rounded-full blur-[120px]"></div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MENU DETAIL MODAL                                         --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($this->selectedMenuItem)
        @php
            $detailItem = $this->selectedMenuItem;
            $inCart = isset($this->cart[$detailItem->id]);
        @endphp
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-black/60 p-0 sm:items-center sm:p-4 backdrop-blur-sm"
             wire:click.self="closeMenuDetail">
            <div class="relative w-full max-w-md rounded-t-3xl bg-white pb-8 shadow-2xl transition-all sm:rounded-2xl overflow-hidden">

                {{-- Image --}}
                <div class="relative aspect-square w-full overflow-hidden bg-[#ebeef3]">
                    @if($detailItem->image)
                        <img src="{{ Storage::url($detailItem->image) }}?v={{ time() }}" alt="{{ $detailItem->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#e61919] to-[#bc000a]">
                            <span class="text-6xl drop-shadow">🍗</span>
                        </div>
                    @endif
                    <button wire:click="closeMenuDetail"
                            class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-full bg-black/40 text-white transition hover:bg-black/60 active:scale-90">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            @if($detailItem->category)
                                <span class="inline-block rounded-md bg-[#ebeef3] px-2.5 py-0.5 text-xs font-semibold text-[#5e3f3b]">
                                    {{ $detailItem->category->name }}
                                </span>
                            @endif
                            <h3 class="mt-2 text-xl font-bold text-[#181c20] leading-snug font-jakarta">{{ $detailItem->name }}</h3>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-lg font-black text-[#bc000a]">
                                Rp {{ number_format($detailItem->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 border-t border-[#e0e3e8] pt-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#5e3f3b]">Deskripsi</h4>
                        <p class="mt-1 text-sm text-[#5e3f3b] leading-relaxed">
                            {{ $detailItem->description ?: 'Tidak ada deskripsi untuk menu ini.' }}
                        </p>
                    </div>

                    <div class="mt-6">
                        @if(! $inCart)
                            <button wire:click="addToCart({{ $detailItem->id }})"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#bc000a] py-3.5 font-bold text-white shadow-lg transition hover:bg-[#c0000b] active:scale-95">
                                <span class="material-symbols-outlined">add_shopping_cart</span>
                                Tambahkan ke Keranjang
                            </button>
                        @else
                            <div class="flex items-center justify-between rounded-xl border border-[#e8bcb6] bg-[#f1f4f9] p-2">
                                <span class="pl-2 text-sm font-semibold text-[#bc000a]">Sudah di keranjang</span>
                                <div class="flex items-center gap-1 bg-[#ebeef3] rounded-lg p-0.5">
                                    <button wire:click="decreaseQty({{ $detailItem->id }})"
                                            class="w-8 h-8 rounded-md bg-white text-[#bc000a] flex items-center justify-center hover:bg-white shadow-sm transition-all active:scale-90">
                                        <span class="material-symbols-outlined text-[16px]">remove</span>
                                    </button>
                                    <span class="min-w-6 text-center text-sm font-bold text-[#181c20] px-1">
                                        {{ $this->cart[$detailItem->id]['quantity'] }}
                                    </span>
                                    <button wire:click="increaseQty({{ $detailItem->id }})"
                                            class="w-8 h-8 rounded-md bg-[#bc000a] text-white flex items-center justify-center hover:bg-[#c0000b] shadow-sm transition-all active:scale-90">
                                        <span class="material-symbols-outlined text-[16px]">add</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MEMBER AREA MODAL                                          --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($showMemberModal)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-black/60 p-0 sm:items-center sm:p-4 backdrop-blur-sm"
             wire:click.self="$set('showMemberModal', false)">
            <div class="relative w-full max-w-md rounded-t-3xl bg-white pb-8 shadow-2xl transition-all sm:rounded-2xl overflow-hidden">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between border-b border-[#e0e3e8] px-6 py-4 bg-[#f1f4f9]">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#fdc003]" style="font-variation-settings:'FILL' 1">star</span>
                        <h3 class="text-base font-bold text-[#181c20] font-jakarta">Area Member</h3>
                    </div>
                    <button wire:click="$set('showMemberModal', false)"
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-[#ebeef3] text-[#5e3f3b] hover:bg-[#e5e8ee] transition active:scale-90">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>

                @if($loggedInMemberId)
                    {{-- Logged in --}}
                    <div class="p-6 space-y-4">
                        <div class="flex items-center gap-4 bg-[#f1f4f9] p-4 rounded-2xl border border-[#e8bcb6]">
                            <div class="w-12 h-12 rounded-full bg-[#bc000a] flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($loggedInMemberName, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-[#181c20] text-base font-jakarta">{{ $loggedInMemberName }}</p>
                                <p class="text-xs text-[#5e3f3b]">
                                    Saldo Poin: <strong class="text-[#bc000a]">{{ number_format($loggedInMemberPoints, 0, ',', '.') }}</strong>
                                </p>
                            </div>
                        </div>

                        <div class="text-sm text-[#5e3f3b] leading-relaxed bg-[#f1f4f9] p-4 rounded-xl border border-[#e0e3e8]">
                            <p class="font-bold text-[#181c20] mb-1">💡 Cara Mendapatkan & Menukar Poin:</p>
                            <ul class="list-disc pl-5 space-y-1 text-xs">
                                <li>Tiap kelipatan belanja <strong>Rp 1.000</strong> mendapat <strong>1 Poin</strong>.</li>
                                <li>Kumpulkan <strong>150 Poin</strong> untuk voucher <strong>1 Paket Nasi Ayam Geprek GRATIS</strong>.</li>
                            </ul>
                        </div>

                        {{-- Reward progress --}}
                        @php
                            $targetPoints   = 150;
                            $currentPoints  = $loggedInMemberPoints;
                            $neededPoints   = max(0, $targetPoints - $currentPoints);
                            $progressPercent = min(100, ($currentPoints / $targetPoints) * 100);
                        @endphp
                        <div class="bg-[#f1f4f9] p-4 rounded-2xl border border-[#e8bcb6] space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm text-[#181c20] flex items-center gap-1.5">🎁 Reward Member</span>
                                <span class="text-xs font-bold text-[#bc000a] bg-[#ffdad5] px-2 py-0.5 rounded-md">
                                    {{ $currentPoints }} / {{ $targetPoints }} Poin
                                </span>
                            </div>
                            <div class="w-full bg-[#e0e3e8] h-2.5 rounded-full overflow-hidden">
                                <div class="bg-[#bc000a] h-full rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                            </div>
                            @if($neededPoints > 0)
                                <p class="text-xs text-[#5e3f3b]">
                                    <strong>{{ $neededPoints }} poin</strong> lagi untuk mendapatkan <strong>1 Paket Nasi Ayam Geprek GRATIS</strong>.
                                </p>
                            @else
                                <p class="text-xs text-green-600 font-bold">🎉 Poin Anda sudah mencapai target! Voucher gratis sedang diproses.</p>
                            @endif
                        </div>

                        <button type="button" wire:click="logoutMember"
                                wire:confirm="Keluar dari akun member?"
                                class="w-full py-3.5 bg-[#ffdad6] text-[#93000a] border border-[#ffdad6] rounded-2xl font-semibold hover:opacity-90 transition active:scale-95">
                            Keluar dari Akun Member
                        </button>
                    </div>

                @else
                    {{-- Login / Register tabs --}}
                    <div class="flex border-b border-[#e0e3e8] bg-white">
                        <button type="button" wire:click="$set('isRegistering', false)"
                            class="flex-1 py-3 text-center text-sm font-bold border-b-2 transition
                                   {{ ! $isRegistering ? 'border-[#bc000a] text-[#bc000a]' : 'border-transparent text-[#5e3f3b]' }}">
                            Masuk Member
                        </button>
                        <button type="button" wire:click="$set('isRegistering', true)"
                            class="flex-1 py-3 text-center text-sm font-bold border-b-2 transition
                                   {{ $isRegistering ? 'border-[#bc000a] text-[#bc000a]' : 'border-transparent text-[#5e3f3b]' }}">
                            Daftar Member
                        </button>
                    </div>

                    <div class="p-6 space-y-3 bg-[#f1f4f9]/50">
                        @if(! $isRegistering)
                            <p class="text-xs text-[#5e3f3b]">Masukkan nomor HP dan PIN member Anda untuk masuk.</p>

                            @if($memberLoginError)
                                <div class="flex items-center gap-2 bg-[#ffdad6] border border-[#ffdad6] text-[#93000a] text-xs px-3 py-2.5 rounded-xl">
                                    <span class="material-symbols-outlined text-[16px]">warning</span>
                                    <span>{{ $memberLoginError }}</span>
                                </div>
                            @endif

                            <input type="tel" wire:model="memberPhone" placeholder="Nomor HP (cth: 08123456789)"
                                   class="w-full px-3.5 py-2.5 border border-[#e0e3e8] rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#bc000a]/30 focus:border-transparent">
                            <input type="password" wire:model="memberPin" placeholder="PIN Member (6 digit)"
                                   maxlength="6" inputmode="numeric" wire:keydown.enter="loginMember"
                                   class="w-full px-3.5 py-2.5 border border-[#e0e3e8] rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#bc000a]/30 focus:border-transparent">

                            <button type="button" wire:click="loginMember"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-60" wire:target="loginMember"
                                    class="w-full py-3.5 bg-[#bc000a] text-white rounded-2xl font-bold hover:bg-[#c0000b] transition active:scale-95 shadow-lg">
                                <span wire:loading.remove wire:target="loginMember">Masuk Member</span>
                                <span wire:loading wire:target="loginMember">Memverifikasi...</span>
                            </button>
                        @else
                            <p class="text-xs text-[#5e3f3b]">Pendaftaran gratis! Dapatkan poin belanja yang bisa ditukar voucher.</p>

                            @if($memberRegisterError)
                                <div class="flex items-center gap-2 bg-[#ffdad6] border border-[#ffdad6] text-[#93000a] text-xs px-3 py-2.5 rounded-xl">
                                    <span class="material-symbols-outlined text-[16px]">warning</span>
                                    <span>{{ $memberRegisterError }}</span>
                                </div>
                            @endif

                            <div>
                                <input type="text" wire:model="registerName" placeholder="Nama Lengkap"
                                       class="w-full px-3.5 py-2.5 border border-[#e0e3e8] rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#bc000a]/30 focus:border-transparent">
                                @error('registerName') <p class="text-xs text-[#ba1a1a] mt-1 pl-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <input type="tel" wire:model="registerPhone" placeholder="Nomor HP (cth: 08123456789)"
                                       class="w-full px-3.5 py-2.5 border border-[#e0e3e8] rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#bc000a]/30 focus:border-transparent">
                                @error('registerPhone') <p class="text-xs text-[#ba1a1a] mt-1 pl-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <input type="password" wire:model="registerPin" placeholder="PIN Baru (6 digit angka)"
                                       maxlength="6" inputmode="numeric"
                                       class="w-full px-3.5 py-2.5 border border-[#e0e3e8] rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#bc000a]/30 focus:border-transparent">
                                @error('registerPin') <p class="text-xs text-[#ba1a1a] mt-1 pl-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <input type="password" wire:model="registerPin_confirmation" placeholder="Konfirmasi PIN"
                                       maxlength="6" inputmode="numeric"
                                       class="w-full px-3.5 py-2.5 border border-[#e0e3e8] rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#bc000a]/30 focus:border-transparent">
                                @error('registerPin_confirmation') <p class="text-xs text-[#ba1a1a] mt-1 pl-1">{{ $message }}</p> @enderror
                            </div>

                            <button type="button" wire:click="registerMember"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-60" wire:target="registerMember"
                                    class="w-full py-3.5 bg-[#bc000a] text-white rounded-2xl font-bold hover:bg-[#c0000b] transition active:scale-95 shadow-lg">
                                <span wire:loading.remove wire:target="registerMember">Daftar & Masuk Member</span>
                                <span wire:loading wire:target="registerMember">Mendaftar...</span>
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- REWARD POPUP (auto-redeem)                                --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if(session()->has('reward_vouchers_redeemed') && !empty(session('reward_vouchers_redeemed')))
        @php $redeemedVouchers = session('reward_vouchers_redeemed'); @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl text-center space-y-4">
                <div class="mx-auto w-16 h-16 rounded-full bg-[#fdc003] flex items-center justify-center text-3xl">🎉</div>

                <div class="space-y-1">
                    <h3 class="text-lg font-extrabold text-[#181c20] font-jakarta">Selamat!</h3>
                    <p class="text-sm text-[#5e3f3b]">Anda berhasil menukarkan 150 poin menjadi voucher:</p>
                </div>

                @foreach($redeemedVouchers as $code)
                    <div class="bg-[#f1f4f9] border-2 border-dashed border-[#e8bcb6] rounded-xl p-3 font-mono text-lg font-black text-[#bc000a] select-all tracking-wider relative">
                        {{ $code }}
                        <span class="absolute -top-2 -right-2 bg-[#bc000a] text-[10px] text-white font-bold px-1.5 py-0.5 rounded-full shadow-sm">SALIN</span>
                    </div>
                @endforeach

                <div class="text-xs text-[#5e3f3b] space-y-1 bg-[#f1f4f9] p-3 rounded-xl border border-[#e0e3e8]">
                    <p class="font-bold text-[#181c20]">🎁 Reward:</p>
                    <p>1 Paket Nasi Ayam Geprek Gratis</p>
                    <p class="text-[10px] text-[#5e3f3b]/70">Masa berlaku: 7 hari sejak ditukarkan</p>
                </div>

                <button type="button" wire:click="closeRewardPopup"
                        class="w-full py-3 bg-[#bc000a] text-white rounded-xl font-bold hover:bg-[#c0000b] transition active:scale-95 shadow-lg">
                    Gunakan Sekarang
                </button>
            </div>
        </div>
    @endif

</div>
