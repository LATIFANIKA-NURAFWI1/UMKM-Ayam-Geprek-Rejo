<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('menu.index') }}" wire:navigate class="text-zinc-400 hover:text-zinc-600">
            <flux:icon name="arrow-left" class="h-5 w-5" />
        </a>
        <div>
            <flux:heading size="xl" level="1">Tambah Menu</flux:heading>
            <flux:text class="mt-0.5">Tambahkan item menu baru ke daftar</flux:text>
        </div>
    </div>

    <form wire:submit="save" class="mx-auto w-full max-w-2xl space-y-5">

        {{-- Gambar --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:label class="mb-3 block font-semibold">Foto Menu</flux:label>

            {{-- Preview --}}
            <div class="mb-4 flex h-40 w-full items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-zinc-300 bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800">
                @if($image)
                    <img src="{{ $image->temporaryUrl() }}" class="h-full w-full object-cover rounded-xl">
                @else
                    <div class="flex flex-col items-center gap-2 text-zinc-400">
                        <flux:icon name="fire" class="h-10 w-10" />
                        <p class="text-sm">Belum ada foto</p>
                    </div>
                @endif
            </div>

            {{-- Upload button (native file input wired to Livewire) --}}
            <div class="flex items-center gap-3">
                <label class="cursor-pointer">
                    <input type="file" wire:model="image" accept="image/*" class="sr-only">
                    <span class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Pilih Foto
                    </span>
                </label>
                <div wire:loading wire:target="image" class="flex items-center gap-2 text-sm text-orange-500">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                    Mengupload...
                </div>
                <p class="text-xs text-zinc-400">JPG, PNG, maks 2MB (Rekomendasi rasio 1:1 / persegi)</p>
            </div>
            @error('image') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Info Utama --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900 space-y-4">
            <flux:field>
                <flux:label>Nama Menu <span class="text-red-500">*</span></flux:label>
                <flux:input wire:model="name" placeholder="cth: Geprek Ori, Es Teh Manis…" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Deskripsi</flux:label>
                <flux:textarea wire:model="description" placeholder="Deskripsi singkat menu (opsional)" rows="2" />
                <flux:error name="description" />
            </flux:field>

            <flux:field>
                <flux:label>Harga (Rp) <span class="text-red-500">*</span></flux:label>
                <flux:input type="number" wire:model="price" placeholder="15000" min="0" />
                <flux:error name="price" />
            </flux:field>

            <flux:field>
                <flux:label>Kategori <span class="text-red-500">*</span></flux:label>
                <select wire:model="category_id"
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700 shadow-sm focus:border-orange-400 focus:outline-none focus:ring-1 focus:ring-orange-400 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
                <flux:error name="category_id" />
            </flux:field>

            <flux:field>
                <flux:checkbox wire:model="is_available" label="Menu tersedia (bisa dipesan)" />
            </flux:field>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-3">
            <flux:button type="button" variant="ghost" href="{{ route('menu.index') }}" wire:navigate>Batal</flux:button>
            <flux:button type="submit" icon="plus"
                wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">Simpan Menu</span>
                <span wire:loading wire:target="save">Menyimpan…</span>
            </flux:button>
        </div>

    </form>

</div>
