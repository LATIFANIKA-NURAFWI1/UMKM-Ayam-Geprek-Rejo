<div class="min-h-screen bg-orange-50 pb-36">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- STICKY HEADER (Logo + Search + Category Tabs)             --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="sticky top-0 z-30 bg-white shadow-sm">
        <div class="mx-auto max-w-md px-4 pt-4 pb-3">

            {{-- Brand + Cart Indicator --}}
            <div class="mb-3 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-orange-400">Self Order</p>
                    <h1 class="text-xl font-extrabold leading-none text-zinc-900">🍗 Geprek Rejo</h1>
                </div>

                {{-- Member and Cart Indicators --}}
                <div class="flex items-center gap-2">
                    @if($loggedInMemberId)
                        <button wire:click="$set('showMemberModal', true)"
                                class="flex items-center gap-1 rounded-full bg-orange-100 px-3 py-1.5 text-xs font-bold text-orange-800 shadow-sm transition active:scale-95 hover:bg-orange-200">
                            <span>⭐ {{ explode(' ', $loggedInMemberName)[0] }} ({{ number_format($loggedInMemberPoints, 0, ',', '.') }} P)</span>
                        </button>
                    @else
                        <button wire:click="$set('showMemberModal', true)"
                                class="flex items-center gap-1 rounded-full border border-orange-300 bg-white px-3 py-1.5 text-xs font-bold text-orange-600 shadow-sm transition active:scale-95 hover:bg-orange-50">
                            <span>⭐ Daftar / Login</span>
                        </button>
                    @endif

                    {{-- Mini cart badge di header --}}
                    @if($this->cartCount > 0)
                        <button
                            wire:click="goToCheckout"
                            class="flex items-center gap-1.5 rounded-full bg-orange-500 px-3 py-1.5 shadow-sm transition active:scale-95 hover:bg-orange-600"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                            <span class="text-xs font-bold text-white">{{ $this->cartCount }}</span>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Search Bar --}}
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchQuery"
                    placeholder="Cari menu favoritmu…"
                    class="w-full rounded-xl border-0 bg-orange-50 py-2.5 pl-10 pr-10 text-sm text-zinc-700 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
                {{-- Clear search --}}
                @if($searchQuery)
                    <button
                        wire:click="$set('searchQuery', '')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full text-zinc-400 transition hover:text-zinc-600"
                        aria-label="Hapus pencarian"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
                {{-- Spinner saat searching --}}
                <div wire:loading wire:target="searchQuery" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="h-4 w-4 animate-spin text-orange-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- ── Category Tabs ───────────────────────────────────────── --}}
        <div class="overflow-x-auto border-t border-orange-100 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <div class="flex gap-2 whitespace-nowrap px-4 py-2.5">

                {{-- "Semua" Tab --}}
                <button
                    wire:click="$set('activeCategory', null)"
                    @class([
                        'flex-shrink-0 rounded-full px-4 py-1.5 text-sm font-medium transition-all',
                        'bg-orange-500 text-white shadow-sm' => $activeCategory === null,
                        'bg-orange-100 text-orange-700 hover:bg-orange-200' => $activeCategory !== null,
                    ])
                >
                    ✨ Semua
                </button>

                @foreach($this->categories as $cat)
                    <button
                        wire:click="$set('activeCategory', {{ $cat->id }})"
                        @class([
                            'flex-shrink-0 rounded-full px-4 py-1.5 text-sm font-medium transition-all',
                            'bg-orange-500 text-white shadow-sm' => $activeCategory === $cat->id,
                            'bg-orange-100 text-orange-700 hover:bg-orange-200' => $activeCategory !== $cat->id,
                        ])
                    >
                        @if($cat->icon)<span>{{ $cat->icon }}</span>@endif {{ $cat->name }}
                    </button>
                @endforeach

            </div>
        </div>
    </div>
    {{-- / STICKY HEADER --}}


    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- FLASH ERROR MESSAGE                                       --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if(session()->has('cart_error'))
        <div class="mx-auto max-w-md px-4 pt-3">
            <div class="flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                {{ session('cart_error') }}
            </div>
        </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MENU GRID                                                  --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="mx-auto max-w-md px-4 py-4">

        @if($this->menuItems->isEmpty())

            {{-- ── Empty State ──────────────────────────────────────────── --}}
            <div class="flex flex-col items-center justify-center pb-8 pt-16 text-center">
                <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-orange-100">
                    <span class="text-4xl">🍽️</span>
                </div>
                <h3 class="text-base font-semibold text-zinc-700">Menu tidak ditemukan</h3>
                <p class="mt-1 text-sm text-zinc-400">
                    @if($searchQuery)
                        Tidak ada menu untuk
                        "<span class="font-medium text-zinc-600">{{ $searchQuery }}</span>"
                    @else
                        Belum ada menu di kategori ini
                    @endif
                </p>
                @if($searchQuery || $activeCategory)
                    <button
                        wire:click="clearFilters"
                        class="mt-4 rounded-full bg-orange-500 px-5 py-2 text-sm font-medium text-white transition hover:bg-orange-600 active:scale-95"
                    >
                        Lihat semua menu
                    </button>
                @endif
            </div>

        @else

            {{-- ── 2-Column Menu Grid ──────────────────────────────────── --}}
            <div
                wire:loading.class="opacity-60 pointer-events-none"
                wire:target="activeCategory, searchQuery"
                class="grid grid-cols-2 gap-3 transition-opacity duration-150"
            >
                @foreach($this->menuItems as $item)
                    @php $inCart = isset($this->cart[$item->id]); @endphp

                    <div class="group relative flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">

                        {{-- ── Menu Image ─────────────────────────────── --}}
                        <div wire:click="showMenuDetail({{ $item->id }})" class="relative aspect-square w-full overflow-hidden cursor-pointer">
                            @if($item->image)
                                <img
                                    src="{{ Storage::url($item->image) }}"
                                    alt="{{ $item->name }}"
                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                    loading="lazy"
                                />
                            @else
                                {{-- Placeholder gradient + chicken emoji --}}
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-orange-400 to-amber-500">
                                    <span class="text-5xl drop-shadow">🍗</span>
                                </div>
                            @endif

                            {{-- Qty badge (top-left, hanya jika ada di cart) --}}
                            @if($inCart)
                                <div class="absolute left-2 top-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-orange-500 px-1.5 text-xs font-bold text-white shadow">
                                    {{ $this->cart[$item->id]['quantity'] }}
                                </div>
                            @endif
                        </div>

                        {{-- ── Item Info ──────────────────────────────── --}}
                        {{-- pb-12 memberi ruang untuk tombol cart di bawah --}}
                        <div wire:click="showMenuDetail({{ $item->id }})" class="flex flex-1 flex-col p-3 pb-12 cursor-pointer">
                            <p class="line-clamp-2 text-sm font-semibold leading-snug text-zinc-900">
                                {{ $item->name }}
                            </p>
                            @if($item->category)
                                <p class="mt-0.5 text-xs text-zinc-400">{{ $item->category->name }}</p>
                            @endif
                            <p class="mt-auto pt-1.5 text-sm font-bold text-orange-600">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- ── Cart Controls (absolute bottom-right) ──── --}}
                        <div class="absolute bottom-3 right-2.5">

                            @if(! $inCart)
                                {{-- Tombol + (tambah ke cart) --}}
                                <button
                                    wire:click="addToCart({{ $item->id }})"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-60 scale-90"
                                    wire:target="addToCart({{ $item->id }})"
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-500 text-white shadow-md transition active:scale-90 hover:bg-orange-600 disabled:cursor-not-allowed"
                                    aria-label="Tambah {{ $item->name }} ke keranjang"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>

                            @else
                                {{-- Qty stepper: [ - qty + ] --}}
                                <div class="flex items-center rounded-full bg-orange-500 px-0.5 shadow-md">

                                    {{-- Tombol - --}}
                                    <button
                                        wire:click="decreaseQty({{ $item->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="decreaseQty({{ $item->id }})"
                                        class="flex h-8 w-8 items-center justify-center rounded-full text-white transition active:bg-orange-600 active:scale-90 disabled:opacity-60"
                                        aria-label="Kurangi {{ $item->name }}"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                        </svg>
                                    </button>

                                    {{-- Qty display --}}
                                    <span class="min-w-5 text-center text-sm font-bold text-white">
                                        {{ $this->cart[$item->id]['quantity'] }}
                                    </span>

                                    {{-- Tombol + --}}
                                    <button
                                        wire:click="increaseQty({{ $item->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="increaseQty({{ $item->id }})"
                                        class="flex h-8 w-8 items-center justify-center rounded-full text-white transition active:bg-orange-600 active:scale-90 disabled:opacity-60"
                                        aria-label="Tambah {{ $item->name }}"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>

                                </div>
                            @endif

                        </div>
                        {{-- / Cart Controls --}}

                    </div>
                    {{-- / Card --}}

                @endforeach
            </div>
            {{-- / Grid --}}



        @endif
    </div>
    {{-- / MENU GRID --}}


    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- FIXED BOTTOM CART BAR                                     --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="fixed bottom-0 left-0 right-0 z-50 border-t border-zinc-100 bg-white/95 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] backdrop-blur-sm">
        <div
            class="mx-auto max-w-md px-4 py-3"
            style="padding-bottom: calc(0.75rem + env(safe-area-inset-bottom, 0px));"
        >
            @if($this->cartCount > 0)

                {{-- ── Active Cart Button ──────────────────────────── --}}
                <button
                    wire:click="goToCheckout"
                    wire:loading.attr="disabled"
                    wire:target="goToCheckout"
                    class="group flex w-full items-center justify-between rounded-2xl bg-orange-500 px-5 py-4 text-white shadow-lg transition active:scale-[0.98] hover:bg-orange-600 disabled:opacity-70"
                >
                    {{-- Kiri: badge jumlah item + label --}}
                    <div class="flex items-center gap-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/25 text-sm font-bold">
                            {{ $this->cartCount }}
                        </span>
                        <span class="font-semibold tracking-wide">Lihat Keranjang</span>
                    </div>

                    {{-- Kanan: total harga + chevron --}}
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold">
                            Rp {{ number_format($this->cartTotal, 0, ',', '.') }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 transition group-hover:translate-x-0.5"
                            fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </button>

            @else

                {{-- ── Empty Cart State ─────────────────────────────── --}}
                <div class="flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-2xl bg-zinc-200 px-5 py-4 text-zinc-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    <span class="font-semibold">Keranjang Kosong</span>
                </div>

            @endif
        </div>
    </div>
    {{-- / BOTTOM CART BAR --}}

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
                
                {{-- Detail Image --}}
                <div class="relative aspect-square w-full overflow-hidden bg-zinc-100">
                    @if($detailItem->image)
                        <img src="{{ Storage::url($detailItem->image) }}" alt="{{ $detailItem->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-orange-400 to-amber-500">
                            <span class="text-6xl drop-shadow">🍗</span>
                        </div>
                    @endif
                    
                    {{-- Close Button inside Image --}}
                    <button wire:click="closeMenuDetail" 
                            class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-full bg-black/40 text-white transition hover:bg-black/60 active:scale-90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Detail Content --}}
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            @if($detailItem->category)
                                <span class="inline-block rounded-md bg-orange-100 px-2.5 py-0.5 text-xs font-semibold text-orange-700">
                                    {{ $detailItem->category->name }}
                                </span>
                            @endif
                            <h3 class="mt-2 text-xl font-bold text-zinc-900 leading-snug">{{ $detailItem->name }}</h3>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-black text-orange-600">
                                Rp {{ number_format($detailItem->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mt-4 border-t border-zinc-100 pt-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-400">Deskripsi</h4>
                        <p class="mt-1 text-sm text-zinc-600 leading-relaxed">
                            {{ $detailItem->description ?: 'Tidak ada deskripsi untuk menu ini.' }}
                        </p>
                    </div>

                    {{-- Action Button (Cart) --}}
                    <div class="mt-6">
                        @if(! $inCart)
                            <button wire:click="addToCart({{ $detailItem->id }})"
                                    class="flex w-full items-center justify-center gap-2 rounded-2xl bg-orange-500 py-3.5 font-bold text-white shadow-lg transition hover:bg-orange-600 active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                                Tambahkan ke Keranjang
                            </button>
                        @else
                            <div class="flex items-center justify-between rounded-2xl border border-orange-200 bg-orange-50/50 p-2">
                                <span class="pl-2 text-sm font-semibold text-orange-700">Sudah di keranjang</span>
                                <div class="flex items-center rounded-full bg-orange-500 px-0.5 shadow-sm">
                                    <button wire:click="decreaseQty({{ $detailItem->id }})"
                                            class="flex h-8 w-8 items-center justify-center rounded-full text-white transition active:bg-orange-600 active:scale-90">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                        </svg>
                                    </button>
                                    <span class="min-w-6 text-center text-sm font-bold text-white">
                                        {{ $this->cart[$detailItem->id]['quantity'] }}
                                    </span>
                                    <button wire:click="increaseQty({{ $detailItem->id }})"
                                            class="flex h-8 w-8 items-center justify-center rounded-full text-white transition active:bg-orange-600 active:scale-90">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
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
                <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4 bg-orange-50">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">⭐</span>
                        <h3 class="text-base font-bold text-zinc-900">Area Member</h3>
                    </div>
                    <button wire:click="$set('showMemberModal', false)" 
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-black/5 text-zinc-500 hover:bg-black/10 transition active:scale-90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @if($loggedInMemberId)
                    {{-- Logged in status --}}
                    <div class="p-6 space-y-4">
                        <div class="flex items-center gap-4 bg-orange-50/50 p-4 rounded-2xl border border-orange-100">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($loggedInMemberName, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-zinc-900 text-base">{{ $loggedInMemberName }}</p>
                                <p class="text-xs text-zinc-500">Saldo Poin: <strong class="text-orange-600">{{ number_format($loggedInMemberPoints, 0, ',', '.') }}</strong></p>
                            </div>
                        </div>

                        <div class="text-sm text-zinc-600 leading-relaxed bg-zinc-50 p-4 rounded-xl border border-zinc-100">
                            <p class="font-bold text-zinc-800 mb-1">💡 Cara Mendapatkan & Menukar Poin:</p>
                            <ul class="list-disc pl-5 space-y-1 text-xs">
                                <li>Tiap kelipatan belanja <strong>Rp 1.000</strong> akan mendapatkan <strong>1 Poin</strong> (belanja Rp 10.000 = 10 Poin).</li>
                                <li>Kumpulkan poin hingga mencapai <strong>150 Poin</strong> untuk otomatis ditukarkan menjadi voucher <strong>1 Paket Nasi Ayam Geprek GRATIS</strong>.</li>
                            </ul>
                        </div>

                        {{-- 🎁 Reward Member Section --}}
                        @php
                            $targetPoints = 150;
                            $currentPoints = $loggedInMemberPoints;
                            $neededPoints = max(0, $targetPoints - $currentPoints);
                            $progressPercent = min(100, ($currentPoints / $targetPoints) * 100);
                        @endphp
                        <div class="bg-gradient-to-br from-orange-50 to-amber-50 p-4 rounded-2xl border border-orange-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm text-zinc-800 flex items-center gap-1.5">🎁 Reward Member</span>
                                <span class="text-xs font-bold text-orange-600 bg-orange-100/60 px-2 py-0.5 rounded-md">
                                    {{ $currentPoints }} / {{ $targetPoints }} Poin
                                </span>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="w-full bg-zinc-200 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-orange-500 h-full rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                            </div>

                            @if($neededPoints > 0)
                                <p class="text-xs text-zinc-600">
                                    <strong>{{ $neededPoints }} poin</strong> lagi untuk mendapatkan <strong>1 Paket Nasi Ayam Geprek GRATIS</strong>.
                                </p>
                            @else
                                <p class="text-xs text-green-600 font-bold">
                                    🎉 Poin Anda sudah mencapai target! Voucher gratis sedang diproses.
                                </p>
                            @endif
                        </div>

                        <button type="button" wire:click="logoutMember"
                                wire:confirm="Keluar dari akun member?"
                                class="w-full py-3.5 bg-red-50 text-red-600 border border-red-200 rounded-2xl font-semibold hover:bg-red-100 transition active:scale-95">
                            Keluar dari Akun Member
                        </button>
                    </div>

                @else
                    {{-- Form tabs (Login/Register) --}}
                    <div class="flex border-b border-gray-200 bg-white">
                        <button type="button" wire:click="$set('isRegistering', false)"
                            class="flex-1 py-3 text-center text-sm font-bold border-b-2 transition
                                   {{ ! $isRegistering ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-400' }}">
                            Masuk Member
                        </button>
                        <button type="button" wire:click="$set('isRegistering', true)"
                            class="flex-1 py-3 text-center text-sm font-bold border-b-2 transition
                                   {{ $isRegistering ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-400' }}">
                            Daftar Member
                        </button>
                    </div>

                    <div class="p-6 space-y-3 bg-zinc-50/50">
                        @if(! $isRegistering)
                            {{-- Login form --}}
                            <p class="text-xs text-zinc-500">Masukkan nomor HP dan PIN member Anda untuk masuk.</p>

                            @if($memberLoginError)
                                <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 text-xs px-3 py-2.5 rounded-xl">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span>{{ $memberLoginError }}</span>
                                </div>
                            @endif

                            <div>
                                <input type="tel" wire:model="memberPhone" placeholder="Nomor HP (cth: 08123456789)"
                                       class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                            </div>
                            <div>
                                <input type="password" wire:model="memberPin" placeholder="PIN Member (6 digit)"
                                       maxlength="6" inputmode="numeric" wire:keydown.enter="loginMember"
                                       class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                            </div>
                            <button type="button" wire:click="loginMember"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-60" wire:target="loginMember"
                                    class="w-full py-3.5 bg-orange-500 text-white rounded-2xl font-bold hover:bg-orange-600 transition active:scale-95 shadow-lg shadow-orange-500/20">
                                <span wire:loading.remove wire:target="loginMember">Masuk Member</span>
                                <span wire:loading wire:target="loginMember">Memverifikasi...</span>
                            </button>
                        @else
                            {{-- Register form --}}
                            <p class="text-xs text-zinc-500">Pendaftaran gratis! Dapatkan poin belanja yang bisa ditukar voucher.</p>

                            @if($memberRegisterError)
                                <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 text-xs px-3 py-2.5 rounded-xl">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span>{{ $memberRegisterError }}</span>
                                </div>
                            @endif

                            <div>
                                <input type="text" wire:model="registerName" placeholder="Nama Lengkap"
                                       class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                                @error('registerName') <p class="text-xs text-red-500 mt-1 pl-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <input type="tel" wire:model="registerPhone" placeholder="Nomor HP (cth: 08123456789)"
                                       class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                                @error('registerPhone') <p class="text-xs text-red-500 mt-1 pl-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <input type="password" wire:model="registerPin" placeholder="PIN Baru (6 digit angka)"
                                       maxlength="6" inputmode="numeric"
                                       class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                                @error('registerPin') <p class="text-xs text-red-500 mt-1 pl-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <input type="password" wire:model="registerPin_confirmation" placeholder="Konfirmasi PIN"
                                       maxlength="6" inputmode="numeric"
                                       class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                                @error('registerPin_confirmation') <p class="text-xs text-red-500 mt-1 pl-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="button" wire:click="registerMember"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-60" wire:target="registerMember"
                                    class="w-full py-3.5 bg-orange-500 text-white rounded-2xl font-bold hover:bg-orange-600 transition active:scale-95 shadow-lg shadow-orange-500/20">
                                <span wire:loading.remove wire:target="registerMember">Daftar & Masuk Member</span>
                                <span wire:loading wire:target="registerMember">Mendaftar...</span>
                            </button>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    @endif

    @if(session()->has('reward_vouchers_redeemed') && !empty(session('reward_vouchers_redeemed')))
        @php
            $redeemedVouchers = session('reward_vouchers_redeemed');
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl text-center space-y-4 animate-scale-up">
                
                {{-- Confetti / Gift icon --}}
                <div class="mx-auto w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center text-3xl">
                    🎉
                </div>

                <div class="space-y-1">
                    <h3 class="text-lg font-extrabold text-zinc-900">Selamat!</h3>
                    <p class="text-sm text-zinc-500">Anda berhasil menukarkan 150 poin menjadi voucher:</p>
                </div>

                @foreach($redeemedVouchers as $code)
                    <div class="bg-orange-50 border-2 border-dashed border-orange-300 rounded-xl p-3 font-mono text-lg font-black text-orange-600 select-all tracking-wider relative group">
                        {{ $code }}
                        <span class="absolute -top-2 -right-2 bg-orange-500 text-[10px] text-white font-bold px-1.5 py-0.5 rounded-full shadow-sm">SALIN</span>
                    </div>
                @endforeach

                <div class="text-xs text-zinc-500 space-y-1 bg-zinc-50 p-3 rounded-xl border border-zinc-100">
                    <p class="font-bold text-zinc-700">🎁 Reward:</p>
                    <p>1 Paket Nasi Ayam Geprek Gratis</p>
                    <p class="text-[10px] text-zinc-400">Masa berlaku: 7 hari sejak ditukarkan</p>
                </div>

                <button type="button" wire:click="closeRewardPopup"
                        class="w-full py-3 bg-orange-500 text-white rounded-xl font-bold hover:bg-orange-600 transition active:scale-95 shadow-lg shadow-orange-500/20">
                    Gunakan Sekarang
                </button>
            </div>
        </div>
    @endif

</div>
