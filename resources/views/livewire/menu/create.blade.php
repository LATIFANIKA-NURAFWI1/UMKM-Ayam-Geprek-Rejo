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

            {{-- Drop Zone --}}
            <div
                id="menu-create-drop-zone"
                class="mb-4 relative flex h-48 w-full items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-zinc-300 bg-zinc-50 transition-all duration-200 cursor-pointer dark:border-zinc-600 dark:bg-zinc-800"
                onclick="document.getElementById('menu-create-image-input').click()"
                ondragover="event.preventDefault(); this.classList.add('border-orange-400','bg-orange-50','dark:bg-zinc-700');"
                ondragleave="this.classList.remove('border-orange-400','bg-orange-50','dark:bg-zinc-700');"
                ondragenter="event.preventDefault();"
                ondrop="event.preventDefault();
                        this.classList.remove('border-orange-400','bg-orange-50','dark:bg-zinc-700');
                        const file = event.dataTransfer.files[0];
                        if (file && file.type.startsWith('image/')) {
                            const input = document.getElementById('menu-create-image-input');
                            const dt = new DataTransfer();
                            dt.items.add(file);
                            input.files = dt.files;
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                        }"
            >
                @if($image)
                    <img src="{{ $image->temporaryUrl() }}" class="h-full w-full object-cover rounded-xl">
                    <div class="absolute inset-0 bg-black/30 flex items-end justify-center pb-3 rounded-xl opacity-0 hover:opacity-100 transition-opacity">
                        <span class="text-white text-xs font-semibold bg-black/50 px-3 py-1 rounded-full">Klik / drag untuk ganti</span>
                    </div>
                @else
                    <div class="flex flex-col items-center gap-2 text-zinc-400 pointer-events-none">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm font-medium">Drag & drop foto ke sini</p>
                        <p class="text-xs">atau klik untuk memilih file</p>
                    </div>
                @endif
            </div>

            {{-- Hidden file input --}}
            <input type="file" id="menu-create-image-input" wire:model="image" accept="image/*" class="sr-only">

            {{-- Upload button --}}
            <div class="flex items-center gap-3">
                <label for="menu-create-image-input" class="cursor-pointer">
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
                <p class="text-xs text-zinc-400">JPG, PNG, WebP, maks 2MB (Rekomendasi rasio 1:1 / persegi)</p>
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
