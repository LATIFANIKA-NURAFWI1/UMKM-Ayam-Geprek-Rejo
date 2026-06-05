<x-layouts::app :title="__('Kategori')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <flux:heading size="xl" level="1">Kategori Menu</flux:heading>
                <flux:text class="mt-1">Kelola kategori untuk mengorganisir menu</flux:text>
            </div>
            <flux:button icon="plus" wire:click="openCreate">Tambah Kategori</flux:button>
        </div>

        {{-- Search --}}
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari kategori…" icon="magnifying-glass" class="w-full sm:w-64" />

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Nama</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500">Urutan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500">Jml Menu</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($categories as $cat)
                        <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @if($cat->icon)
                                        <span class="text-lg">{{ $cat->icon }}</span>
                                    @endif
                                    <span class="font-medium text-zinc-900 dark:text-white">{{ $cat->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-zinc-500">{{ $cat->sort_order }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-700 dark:bg-zinc-700 dark:text-zinc-300">
                                    {{ $cat->menu_items_count }} menu
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $cat->is_active
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400'
                                        : 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400' }}">
                                    {{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <flux:button size="sm" variant="ghost" icon="pencil" wire:click="openEdit({{ $cat->id }})">Edit</flux:button>
                                    <flux:button size="sm" variant="danger" icon="trash"
                                        wire:click="delete({{ $cat->id }})"
                                        wire:confirm="Hapus kategori '{{ $cat->name }}'?">Hapus</flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-sm text-zinc-400">
                                Belum ada kategori. Tambahkan kategori pertama Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $categories->links() }}</div>

        {{-- Modal --}}
        <flux:modal wire:model.self="showModal" class="max-w-md">
            <form wire:submit="save" class="space-y-4 p-6">
                <flux:heading size="lg">{{ $editId ? 'Edit' : 'Tambah' }} Kategori</flux:heading>

                <flux:field>
                    <flux:label>Nama Kategori <flux:required /></flux:label>
                    <flux:input wire:model="name" placeholder="cth: Geprek, Minuman…" />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Ikon (Emoji)</flux:label>
                    <flux:input wire:model="icon" placeholder="🍗" />
                </flux:field>

                <flux:field>
                    <flux:label>Urutan Tampil</flux:label>
                    <flux:input type="number" wire:model="sort_order" min="0" />
                </flux:field>

                <flux:field>
                    <flux:checkbox wire:model="is_active" label="Aktif" />
                </flux:field>

                <div class="flex justify-end gap-3 pt-2">
                    <flux:button type="button" variant="ghost" wire:click="$set('showModal', false)">Batal</flux:button>
                    <flux:button type="submit">Simpan</flux:button>
                </div>
            </form>
        </flux:modal>

    </div>
</x-layouts::app>
