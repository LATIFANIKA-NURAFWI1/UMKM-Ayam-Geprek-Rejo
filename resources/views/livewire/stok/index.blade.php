<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

    @if(session('status'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed right-6 top-6 z-50 flex items-center gap-3 rounded-2xl bg-green-500 px-5 py-3.5 text-white shadow-2xl">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span class="text-sm font-semibold">{{ session('status') }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Stok Bahan Baku</flux:heading>
            <flux:text class="mt-1">Pantau dan kelola persediaan bahan baku dapur</flux:text>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-orange-600 active:scale-95">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Bahan
        </button>
    </div>

    {{-- Summary Cards --}}
    @php
        $total     = $ingredients->total();
        $lowCount  = $ingredients->filter(fn($i) => $i->current_stock <= $i->minimum_stock)->count();
        $okCount   = $total - $lowCount;
    @endphp
    <div class="grid grid-cols-3 gap-4">
        <div class="flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-zinc-100 text-2xl">📦</div>
            <div>
                <p class="text-xs text-zinc-500">Total Bahan</p>
                <p class="text-2xl font-black text-zinc-900">{{ $total }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-2xl">✅</div>
            <div>
                <p class="text-xs text-emerald-600">Stok Aman</p>
                <p class="text-2xl font-black text-emerald-800">{{ $okCount }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 rounded-2xl border border-red-200 bg-red-50 p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-2xl">⚠️</div>
            <div>
                <p class="text-xs text-red-600">Stok Rendah</p>
                <p class="text-2xl font-black text-red-800">{{ $lowCount }}</p>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari bahan baku…"
                class="w-full rounded-xl border border-zinc-300 py-2 pl-10 pr-4 text-sm text-zinc-700 placeholder-zinc-400 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
        </div>
        <select wire:model.live="filterStatus"
            class="rounded-xl border border-zinc-300 px-4 py-2 text-sm text-zinc-700 focus:border-orange-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200">
            <option value="">Semua Status</option>
            <option value="low">⚠️ Stok Rendah</option>
            <option value="ok">✅ Stok Aman</option>
        </select>
    </div>


    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
            <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Nama Bahan</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Stok Saat Ini</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Threshold</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Harga/Unit</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500">Status</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($ingredients as $item)
                    @php
                        $isLow = (float) $item->current_stock <= (float) $item->minimum_stock;
                        $pct   = $item->minimum_stock > 0
                            ? min(100, round(($item->current_stock / ($item->minimum_stock * 3)) * 100))
                            : 100;
                    @endphp
                    <tr @class([
                        'group transition',
                        'bg-red-50/50 hover:bg-red-50 dark:bg-red-950/20 dark:hover:bg-red-950/30' => $isLow,
                        'hover:bg-zinc-50 dark:hover:bg-zinc-800/40' => !$isLow,
                    ])>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($isLow)
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-red-100 text-sm">⚠️</span>
                                @else
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-zinc-100 text-sm">🥬</span>
                                @endif
                                <span class="font-semibold text-zinc-900 dark:text-white">{{ $item->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex flex-col items-end gap-1">
                                <span @class(['text-sm font-bold', 'text-red-600' => $isLow, 'text-zinc-900 dark:text-white' => !$isLow])>
                                    {{ number_format($item->current_stock, 2) }} {{ $item->unit }}
                                </span>
                                <div class="h-1.5 w-20 overflow-hidden rounded-full bg-zinc-200">
                                    <div class="h-full rounded-full transition-all {{ $isLow ? 'bg-red-500' : 'bg-emerald-500' }}"
                                        style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-right text-sm text-zinc-500">
                            {{ number_format($item->minimum_stock, 2) }} {{ $item->unit }}
                        </td>
                        <td class="px-5 py-4 text-right text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Rp {{ number_format($item->unit_cost, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($isLow)
                                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                    ⚠ Stok Rendah
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                    ✅ Aman
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openAdjust({{ $item->id }})"
                                    class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-100 active:scale-95">
                                    ➕ Restock
                                </button>
                                <button wire:click="openEdit({{ $item->id }})"
                                    class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-600 transition hover:bg-zinc-50 active:scale-95 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    ✏️ Edit
                                </button>
                                <button wire:click="delete({{ $item->id }})"
                                    wire:confirm="Hapus bahan '{{ $item->name }}'?"
                                    class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-100 active:scale-95">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <span class="text-5xl">🥬</span>
                                <p class="font-semibold text-zinc-500">Belum ada data bahan baku</p>
                                <p class="text-sm text-zinc-400">Klik "Tambah Bahan" untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($ingredients->hasPages())
            <div class="border-t border-zinc-100 px-5 py-4 dark:border-zinc-800">{{ $ingredients->links() }}</div>
        @endif
    </div>


    {{-- ═══════════════ MODAL: CREATE / EDIT ═══════════════ --}}
    <div x-data="{ open: @entangle('showForm') }" x-show="open" x-on:keydown.escape.window="$wire.set('showForm', false)"
        class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center" style="display: none;">
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm" x-on:click="$wire.set('showForm', false)"></div>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95"
            class="relative z-50 w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-zinc-900">

            <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-5 dark:border-zinc-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-xl">🥬</div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ $editingId ? 'Edit Bahan Baku' : 'Tambah Bahan Baku' }}</h3>
                </div>
                <button wire:click="$set('showForm', false)" class="flex h-8 w-8 items-center justify-center rounded-full text-zinc-400 transition hover:bg-zinc-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4 px-6 py-5">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Nama Bahan *</label>
                        <input type="text" wire:model="name" placeholder="Contoh: Ayam Segar"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Satuan *</label>
                        <input type="text" wire:model="unit" placeholder="kg / liter / pcs"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
                        @error('unit') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Harga per Unit (Rp) *</label>
                        <input type="number" wire:model="unit_cost" min="0" placeholder="0"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
                        @error('unit_cost') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Stok Awal *</label>
                        <input type="number" wire:model="current_stock" step="0.01" min="0" placeholder="0"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
                        @error('current_stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Threshold Peringatan *</label>
                        <input type="number" wire:model="minimum_stock" step="0.01" min="0" placeholder="10"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
                        @error('minimum_stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-3 border-t border-zinc-100 px-6 py-4 dark:border-zinc-800">
                <button wire:click="$set('showForm', false)" class="flex-1 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-600 transition hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">Batal</button>
                <button wire:click="save" wire:loading.attr="disabled" wire:target="save"
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-600 disabled:opacity-60">
                    <span wire:loading.remove wire:target="save">{{ $editingId ? '💾 Perbarui' : '✅ Simpan' }}</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                    </span>
                </button>
            </div>
        </div>
    </div>


    {{-- ═══════════════ MODAL: RESTOCK / ADJUSTMENT ═══════════════ --}}
    <div x-data="{ open: @entangle('showAdjustModal') }" x-show="open" x-on:keydown.escape.window="$wire.set('showAdjustModal', false)"
        class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm" x-on:click="$wire.set('showAdjustModal', false)"></div>
        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="relative z-50 w-full max-w-sm overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-zinc-900">
            <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-5 dark:border-zinc-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-xl">➕</div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Restock / Koreksi Stok</h3>
                </div>
                <button wire:click="$set('showAdjustModal', false)" class="flex h-8 w-8 items-center justify-center rounded-full text-zinc-400 transition hover:bg-zinc-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="space-y-4 px-6 py-5">
                <p class="text-sm text-zinc-500">Masukkan jumlah positif untuk restock, negatif untuk koreksi pengurangan.</p>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Jumlah (+/-) *</label>
                    <input type="number" wire:model="adjustQty" step="0.01" placeholder="Contoh: +50 atau -5"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
                    @error('adjustQty') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Catatan</label>
                    <input type="text" wire:model="adjustNote" placeholder="Opsional"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
                </div>
            </div>
            <div class="flex gap-3 border-t border-zinc-100 px-6 py-4 dark:border-zinc-800">
                <button wire:click="$set('showAdjustModal', false)" class="flex-1 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-600 transition hover:bg-zinc-50">Batal</button>
                <button wire:click="applyAdjustment" wire:loading.attr="disabled" wire:target="applyAdjustment"
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700 disabled:opacity-60">
                    <span wire:loading.remove wire:target="applyAdjustment">✅ Terapkan</span>
                    <span wire:loading wire:target="applyAdjustment" class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                    </span>
                </button>
            </div>
        </div>
    </div>

</div>
