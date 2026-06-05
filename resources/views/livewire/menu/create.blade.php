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
            <div class="flex items-center gap-4">
                <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800">
                    @if($image)
                        <img src="{{ $image->temporaryUrl() }}" class="h-full w-full object-cover">
                    @else
                        <flux:icon name="fire" class="h-8 w-8 text-zinc-300 dark:text-zinc-600" />
                    @endif
                </div>
                <div>
                    <input type="file" wire:model="image" accept="image/*" id="image-upload"
                        class="hidden">
                    <label for="image-upload"
                        class="cursor-pointer rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">
                        Pilih Foto
                    </label>
                    <p class="mt-1.5 text-xs text-zinc-400">JPG, PNG, maks 2MB</p>
                    @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
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

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>Harga (Rp) <span class="text-red-500">*</span></flux:label>
                    <flux:input type="number" wire:model="price" placeholder="15000" min="0" />
                    <flux:error name="price" />
                </flux:field>

                <flux:field>
                    <flux:label>Urutan Tampil</flux:label>
                    <flux:input type="number" wire:model="sort_order" min="0" />
                </flux:field>
            </div>

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
            <flux:button type="submit" icon="plus">Simpan Menu</flux:button>
        </div>

    </form>

</div>
