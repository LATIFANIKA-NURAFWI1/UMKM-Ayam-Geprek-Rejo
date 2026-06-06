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
                        <div class="relative aspect-square w-full overflow-hidden">
                            @if($item->image)
                                <img
                                    src="{{ asset('storage/menu/' . $item->image) }}"
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
                        <div class="flex flex-1 flex-col p-3 pb-12">
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

</div>
