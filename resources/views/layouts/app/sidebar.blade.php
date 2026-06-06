<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>

                {{-- ── Utama ──────────────────────────────────── --}}
                <flux:sidebar.group :heading="__('Utama')" class="grid">
                    <flux:sidebar.item icon="home"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                {{-- ── Manajemen Menu ─────────────────────────── --}}
                <flux:sidebar.group :heading="__('Manajemen Menu')" class="grid">
                    <flux:sidebar.item icon="fire"
                        :href="route('menu.index')"
                        :current="request()->routeIs('menu.*')"
                        wire:navigate>
                        {{ __('Menu Makanan') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="tag"
                        :href="route('kategori.index')"
                        :current="request()->routeIs('kategori.*')"
                        wire:navigate>
                        {{ __('Kategori') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                {{-- ── Transaksi ───────────────────────────────── --}}
                <flux:sidebar.group :heading="__('Transaksi')" class="grid">
                    <flux:sidebar.item icon="shopping-cart"
                        :href="route('pesanan.index')"
                        :current="request()->routeIs('pesanan.*')"
                        wire:navigate>
                        {{ __('Pesanan') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="users"
                        :href="route('member.index')"
                        :current="request()->routeIs('member.*')"
                        wire:navigate>
                        {{ __('Member') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                {{-- ── Operasional ─────────────────────────────── --}}
                <flux:sidebar.group :heading="__('Operasional')" class="grid">
                    <flux:sidebar.item icon="archive-box"
                        :href="route('stok.index')"
                        :current="request()->routeIs('stok.*')"
                        wire:navigate>
                        {{ __('Stok Bahan') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="receipt-percent"
                        :href="route('pengeluaran.index')"
                        :current="request()->routeIs('pengeluaran.*')"
                        wire:navigate>
                        {{ __('Pengeluaran') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="ticket"
                        :href="route('voucher.index')"
                        :current="request()->routeIs('voucher.*')"
                        wire:navigate>
                        {{ __('Voucher') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                {{-- ── Laporan ─────────────────────────────────── --}}
                <flux:sidebar.group :heading="__('Laporan')" class="grid">
                    <flux:sidebar.item icon="chart-bar"
                        :href="route('laporan.index')"
                        :current="request()->routeIs('laporan.*')"
                        wire:navigate>
                        {{ __('Laporan Penjualan') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

            </flux:sidebar.nav>

            <flux:spacer />

            {{-- ── Theme Toggle ────────────────────────────────────────────── --}}
            <div x-data="{
                theme: localStorage.getItem('flux-theme') ?? 'system',
                apply(t) {
                    this.theme = t;
                    localStorage.setItem('flux-theme', t);
                    document.documentElement.classList.toggle('dark',
                        t === 'dark' || (t === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)
                    );
                },
                init() { this.apply(this.theme); }
            }" class="px-3 pb-2">
                <p class="mb-1.5 px-1 text-[10px] font-semibold uppercase tracking-wide text-zinc-400">Tema</p>
                <div class="flex rounded-xl border border-zinc-200 bg-zinc-100 p-1 dark:border-zinc-700 dark:bg-zinc-800">
                    <button @click="apply('light')"
                        :class="theme==='light' ? 'bg-white shadow text-orange-500 dark:bg-zinc-700' : 'text-zinc-400 hover:text-zinc-600'"
                        class="flex flex-1 items-center justify-center gap-1 rounded-lg py-1.5 text-xs font-semibold transition">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 110 10A5 5 0 0112 7z"/></svg>
                        Terang
                    </button>
                    <button @click="apply('dark')"
                        :class="theme==='dark' ? 'bg-white shadow text-orange-500 dark:bg-zinc-700' : 'text-zinc-400 hover:text-zinc-600'"
                        class="flex flex-1 items-center justify-center gap-1 rounded-lg py-1.5 text-xs font-semibold transition">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                        Gelap
                    </button>
                    <button @click="apply('system')"
                        :class="theme==='system' ? 'bg-white shadow text-orange-500 dark:bg-zinc-700' : 'text-zinc-400 hover:text-zinc-600'"
                        class="flex flex-1 items-center justify-center gap-1 rounded-lg py-1.5 text-xs font-semibold transition">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Auto
                    </button>
                </div>
            </div>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        {{-- ── Mobile Header ─────────────────────────────────── --}}
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        {{-- Flash notification global (sukses/error/warning/info) --}}
        <x-flash-notification />

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
