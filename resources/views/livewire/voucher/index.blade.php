<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

    {{-- Flash Message --}}
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
            <flux:heading size="xl" level="1">Manajemen Voucher</flux:heading>
            <flux:text class="mt-1">Buat dan kelola kode diskon untuk pelanggan</flux:text>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-orange-600 active:scale-95">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Buat Voucher
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kode voucher…"
                class="w-full rounded-xl border border-zinc-300 py-2 pl-10 pr-4 text-sm text-zinc-700 placeholder-zinc-400 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
        </div>
        <select wire:model.live="filterStatus"
            class="rounded-xl border border-zinc-300 px-4 py-2 text-sm text-zinc-700 focus:border-orange-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Nonaktif</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
            <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Kode</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Tipe Diskon</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Periode</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500">Penggunaan</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500">Status</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($vouchers as $v)
                    @php
                        $isExpired = $v->expires_at ? $v->expires_at->isPast() : false;
                        $isActive  = $v->is_active && !$isExpired;
                        $quotaFull = $v->max_uses && $v->uses_count >= $v->max_uses;
                    @endphp
                    <tr class="group transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-lg">🎟️</div>
                                <div>
                                    <p class="font-mono text-sm font-bold tracking-wider text-zinc-900 dark:text-white">{{ $v->code }}</p>
                                    @if($v->minimum_order > 0)
                                        <p class="text-xs text-zinc-400">Min. Rp {{ number_format($v->minimum_order, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            @if($v->discount_type === 'percentage')
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-sm font-bold text-blue-700">
                                    📉 {{ $v->discount_value }}%
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-bold text-emerald-700">
                                    💰 Rp {{ number_format($v->discount_value, 0, ',', '.') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-xs text-zinc-500">
                                {{ $v->starts_at?->translatedFormat('d M Y') ?? '—' }}
                                <span class="mx-1">→</span>
                                {{ $v->expires_at?->translatedFormat('d M Y') ?? 'Tanpa batas' }}
                            </p>
                            @if($isExpired)
                                <span class="mt-1 inline-block rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Kadaluarsa</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="{{ $quotaFull ? 'text-red-600 font-bold' : 'text-zinc-600' }} text-sm">
                                {{ $v->uses_count }}
                                @if($v->max_uses)
                                    / {{ $v->max_uses }}
                                @else
                                    / ∞
                                @endif
                            </span>
                            @if($quotaFull)
                                <p class="text-xs text-red-500 mt-0.5">Kuota Habis</p>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <button wire:click="toggleActive({{ $v->id }})" class="group/toggle">
                                @if($isActive)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 transition group-hover/toggle:bg-emerald-200">
                                        ✅ Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-100 px-3 py-1 text-xs font-bold text-zinc-500 transition group-hover/toggle:bg-zinc-200">
                                        ⏸ Nonaktif
                                    </span>
                                @endif
                            </button>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openEdit({{ $v->id }})"
                                    class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-600 transition hover:border-zinc-300 hover:bg-zinc-50 active:scale-95 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    ✏️ Edit
                                </button>
                                <button wire:click="confirmDelete({{ $v->id }})"
                                    class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-100 active:scale-95">
                                    🗑️ Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <span class="text-5xl">🎟️</span>
                                <p class="font-semibold text-zinc-500">Belum ada voucher</p>
                                <p class="text-sm text-zinc-400">Klik "Buat Voucher" untuk membuat voucher diskon pertama</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($vouchers->hasPages())
            <div class="border-t border-zinc-100 px-5 py-4 dark:border-zinc-800">{{ $vouchers->links() }}</div>
        @endif
    </div>


    {{-- ═══════════════ MODAL: FORM ═══════════════ --}}
    <div x-data="{ open: @entangle('showForm') }" x-show="open" x-on:keydown.escape.window="$wire.closeForm()"
        class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center" style="display: none;">
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm" x-on:click="$wire.closeForm()"></div>

        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95"
            class="relative z-50 w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-zinc-900">

            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-5 dark:border-zinc-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-xl">🎟️</div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
                        {{ $editingId ? 'Edit Voucher' : 'Buat Voucher Baru' }}
                    </h3>
                </div>
                <button wire:click="closeForm" class="flex h-8 w-8 items-center justify-center rounded-full text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="max-h-[70vh] overflow-y-auto px-6 py-5 space-y-4">

                {{-- Kode --}}
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Kode Voucher *</label>
                    <input type="text" wire:model="formCode" placeholder="Contoh: GEPREK20" maxlength="50"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 font-mono text-sm uppercase tracking-wider placeholder-zinc-400 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
                        style="text-transform: uppercase;"
                    />
                    @error('formCode') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Tipe Diskon --}}
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Tipe Diskon *</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border-2 p-4 transition {{ $formDiscountType === 'percentage' ? 'border-orange-400 bg-orange-50' : 'border-zinc-200 hover:border-zinc-300' }}">
                            <input type="radio" wire:model.live="formDiscountType" value="percentage" class="sr-only">
                            <span class="text-2xl">📉</span>
                            <span class="text-sm font-semibold {{ $formDiscountType === 'percentage' ? 'text-orange-700' : 'text-zinc-500' }}">Persentase (%)</span>
                        </label>
                        <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border-2 p-4 transition {{ $formDiscountType === 'fixed' ? 'border-orange-400 bg-orange-50' : 'border-zinc-200 hover:border-zinc-300' }}">
                            <input type="radio" wire:model.live="formDiscountType" value="fixed" class="sr-only">
                            <span class="text-2xl">💰</span>
                            <span class="text-sm font-semibold {{ $formDiscountType === 'fixed' ? 'text-orange-700' : 'text-zinc-500' }}">Nominal (Rp)</span>
                        </label>
                    </div>
                    @error('formDiscountType') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Nilai Diskon + Min Purchase --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                            Nilai Diskon * <span class="text-zinc-400">({{ $formDiscountType === 'percentage' ? '%' : 'Rp' }})</span>
                        </label>
                        <input type="number" wire:model="formDiscountValue" placeholder="{{ $formDiscountType === 'percentage' ? '20' : '10000' }}" min="0.01"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
                        @error('formDiscountValue') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Min. Pembelian (Rp)</label>
                        <input type="number" wire:model="formMinPurchase" placeholder="0" min="0"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
                        @error('formMinPurchase') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Periode --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Berlaku Mulai *</label>
                        <input type="date" wire:model="formStartDate"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
                        @error('formStartDate') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Berlaku Sampai *</label>
                        <input type="date" wire:model="formEndDate"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
                        @error('formEndDate') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Max Uses + Is Active --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                            Maks. Penggunaan <span class="text-zinc-400">(kosong = tak terbatas)</span>
                        </label>
                        <input type="number" wire:model="formMaxUses" placeholder="∞" min="1"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"/>
                        @error('formMaxUses') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex flex-col justify-end pb-1">
                        <label class="flex cursor-pointer items-center gap-3">
                            <div class="relative" x-data="{ on: @entangle('formIsActive') }">
                                <input type="checkbox" wire:model.live="formIsActive" class="sr-only peer"/>
                                <div class="h-6 w-11 rounded-full bg-zinc-200 transition peer-checked:bg-orange-500 dark:bg-zinc-700"></div>
                                <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Aktifkan Voucher</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex gap-3 border-t border-zinc-100 px-6 py-4 dark:border-zinc-800">
                <button wire:click="closeForm" class="flex-1 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-600 transition hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">Batal</button>
                <button wire:click="saveVoucher" wire:loading.attr="disabled" wire:target="saveVoucher"
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-600 disabled:opacity-60">
                    <span wire:loading.remove wire:target="saveVoucher">{{ $editingId ? '💾 Simpan Perubahan' : '✅ Buat Voucher' }}</span>
                    <span wire:loading wire:target="saveVoucher" class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>


    {{-- ═══════════════ MODAL: DELETE CONFIRM ═══════════════ --}}
    <div x-data x-show="$wire.deletingId !== null" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div x-show="$wire.deletingId !== null" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm" x-on:click="$wire.cancelDelete()"></div>
        <div x-show="$wire.deletingId !== null" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="relative z-50 w-full max-w-sm overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-zinc-900">
            <div class="p-6 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100"><span class="text-3xl">🗑️</span></div>
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Hapus Voucher?</h3>
                <p class="mt-2 text-sm text-zinc-500">Voucher yang sudah dihapus tidak dapat dipulihkan.</p>
            </div>
            <div class="flex gap-3 border-t border-zinc-100 px-6 py-4 dark:border-zinc-800">
                <button wire:click="cancelDelete" class="flex-1 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-600 transition hover:bg-zinc-50">Batal</button>
                <button wire:click="delete" wire:loading.attr="disabled" wire:target="delete"
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-red-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-600 disabled:opacity-60">
                    <span wire:loading.remove wire:target="delete">Ya, Hapus</span>
                    <span wire:loading wire:target="delete" class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                    </span>
                </button>
            </div>
        </div>
    </div>

</div>
