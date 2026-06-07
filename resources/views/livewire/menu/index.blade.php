<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <flux:heading size="xl" level="1">Menu Makanan</flux:heading>
            <flux:text class="mt-1">Kelola semua item menu Geprek Rejo</flux:text>
        </div>
        <flux:button icon="plus" href="{{ route('menu.create') }}" wire:navigate>
            Tambah Menu
        </flux:button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari nama menu…" icon="magnifying-glass" class="w-full sm:w-64" />

        <select wire:model.live="kategori"
            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700 shadow-sm focus:border-orange-400 focus:outline-none focus:ring-1 focus:ring-orange-400 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 sm:w-48">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="status"
            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700 shadow-sm focus:border-orange-400 focus:outline-none focus:ring-1 focus:ring-orange-400 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 sm:w-44">
            <option value="">Semua Status</option>
            <option value="1">Tersedia</option>
            <option value="0">Tidak Tersedia</option>
        </select>
    </div>

    {{-- Grid Menu --}}
    @if($menuItems->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-300 py-16 dark:border-zinc-700">
            <flux:icon name="fire" class="mb-3 h-12 w-12 text-zinc-300 dark:text-zinc-600" />
            <p class="font-medium text-zinc-500">Tidak ada menu ditemukan</p>
            <p class="mt-1 text-sm text-zinc-400">Coba ubah filter atau tambahkan menu baru</p>
        </div>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            @foreach($menuItems as $item)
                <div class="group relative overflow-hidden rounded-xl border border-zinc-200 bg-white transition hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900">

                    {{-- Image --}}
                    <div class="relative aspect-square overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}?v={{ time() }}" alt="{{ $item->name }}"
                                class="h-full w-full object-cover transition group-hover:scale-105">
                        @else
                            <div class="flex h-full items-center justify-center">
                                <flux:icon name="fire" class="h-10 w-10 text-zinc-300 dark:text-zinc-600" />
                            </div>
                        @endif

                        {{-- Status Badge --}}
                        <span class="absolute right-2 top-2 rounded-full px-2 py-0.5 text-xs font-semibold
                            {{ $item->is_available
                                ? 'bg-green-100 text-green-700 dark:bg-green-900/60 dark:text-green-400'
                                : 'bg-red-100 text-red-700 dark:bg-red-900/60 dark:text-red-400' }}">
                            {{ $item->is_available ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>

                    {{-- Info --}}
                    <div class="p-3">
                        <p class="truncate text-sm font-semibold text-zinc-900 dark:text-white">{{ $item->name }}</p>
                        <p class="text-xs text-zinc-400">{{ $item->category?->name ?? '-' }}</p>
                        <p class="mt-1.5 text-sm font-bold text-orange-600 dark:text-orange-400">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex border-t border-zinc-100 dark:border-zinc-800">
                        <button wire:click="toggleAvailable({{ $item->id }})"
                            class="flex flex-1 items-center justify-center gap-1 py-2 text-xs text-zinc-500 transition hover:bg-zinc-50 hover:text-zinc-700 dark:hover:bg-zinc-800">
                            <flux:icon name="{{ $item->is_available ? 'eye-slash' : 'eye' }}" class="h-3.5 w-3.5" />
                            {{ $item->is_available ? 'Nonaktif' : 'Aktifkan' }}
                        </button>
                        <a href="{{ route('menu.edit', $item->id) }}" wire:navigate
                            class="flex flex-1 items-center justify-center gap-1 border-x border-zinc-100 py-2 text-xs text-zinc-500 transition hover:bg-zinc-50 hover:text-zinc-700 dark:border-zinc-800 dark:hover:bg-zinc-800">
                            <flux:icon name="pencil" class="h-3.5 w-3.5" />
                            Edit
                        </a>
                        <button wire:click="delete({{ $item->id }})"
                            wire:confirm="Hapus menu '{{ $item->name }}'?"
                            class="flex flex-1 items-center justify-center gap-1 py-2 text-xs text-red-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20">
                            <flux:icon name="trash" class="h-3.5 w-3.5" />
                            Hapus
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="mt-2">{{ $menuItems->links() }}</div>
    @endif

</div>
