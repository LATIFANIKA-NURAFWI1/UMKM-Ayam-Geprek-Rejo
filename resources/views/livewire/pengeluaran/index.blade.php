<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

    {{-- ═══════════════════════════════════════════════════════════════════
         FLASH MESSAGE
    ═══════════════════════════════════════════════════════════════════ --}}
    @if(session('status'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3500)"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed right-6 top-6 z-50 flex items-center gap-3 rounded-2xl bg-green-500 px-5 py-3.5 text-white shadow-2xl"
        >
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-sm font-semibold">{{ session('status') }}</span>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════
         HEADER
    ═══════════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Manajemen Pengeluaran</flux:heading>
            <flux:text class="mt-1">Catat dan pantau semua biaya operasional warung</flux:text>
        </div>
        <button
            wire:click="openCreate"
            class="flex items-center gap-2 rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-orange-600 active:scale-95"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Catat Pengeluaran
        </button>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         SUMMARY CARDS — Per Kategori
    ═══════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-3 xl:grid-cols-6">
        {{-- Total Card --}}
        <div class="col-span-2 flex items-center gap-4 rounded-2xl border border-red-200 bg-red-50 p-5 lg:col-span-1 xl:col-span-2">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-red-600">Total Bulan Ini</p>
                <p class="mt-0.5 text-xl font-black text-red-700">Rp {{ number_format($this->totalBulanIni, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Per-Category Cards --}}
        @php
            $catColors = [
                'bahan_baku'  => ['bg-emerald-50', 'text-emerald-600', 'bg-emerald-100', '🥦'],
                'operasional' => ['bg-blue-50',    'text-blue-600',    'bg-blue-100',    '⚡'],
                'gaji'        => ['bg-purple-50',  'text-purple-600',  'bg-purple-100',  '👤'],
                'perawatan'   => ['bg-amber-50',   'text-amber-600',   'bg-amber-100',   '🔧'],
                'marketing'   => ['bg-orange-50',  'text-orange-600',  'bg-orange-100',  '📢'],
                'lainnya'     => ['bg-zinc-50',    'text-zinc-600',    'bg-zinc-100',    '📋'],
            ];
        @endphp
        @foreach(\App\Livewire\Pengeluaran\Index::CATEGORIES as $cat)
            @php [$bgCard, $textColor, $iconBg, $icon] = $catColors[$cat]; @endphp
            <div class="flex flex-col gap-1 rounded-2xl border border-zinc-200 {{ $bgCard }} p-4">
                <div class="flex items-center gap-2">
                    <span class="text-base">{{ $icon }}</span>
                    <p class="text-xs font-medium {{ $textColor }}">{{ ucwords(str_replace('_', ' ', $cat)) }}</p>
                </div>
                <p class="text-sm font-bold text-zinc-900">
                    Rp {{ number_format($this->categoryTotals[$cat] ?? 0, 0, ',', '.') }}
                </p>
            </div>
        @endforeach
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         FILTERS
    ═══════════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-wrap items-center gap-3">
        <input
            type="month"
            wire:model.live="bulan"
            class="rounded-xl border border-zinc-300 px-4 py-2 text-sm text-zinc-700 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
        />
        <select
            wire:model.live="filterKategori"
            class="rounded-xl border border-zinc-300 px-4 py-2 text-sm text-zinc-700 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
        >
            <option value="">Semua Kategori</option>
            @foreach(\App\Livewire\Pengeluaran\Index::CATEGORIES as $cat)
                <option value="{{ $cat }}">{{ ucwords(str_replace('_', ' ', $cat)) }}</option>
            @endforeach
        </select>
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Cari deskripsi…"
                class="w-full rounded-xl border border-zinc-300 py-2 pl-10 pr-4 text-sm text-zinc-700 placeholder-zinc-400 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
            />
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         TABLE
    ═══════════════════════════════════════════════════════════════════ --}}
    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
            <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Tanggal</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Kategori</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Deskripsi</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Jumlah</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($expenses as $expense)
                    <tr class="group transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                        <td class="px-5 py-3.5 text-sm text-zinc-500">
                            {{ \Carbon\Carbon::parse($expense->expense_date)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-5 py-3.5">
                            @php
                                $catBadge = [
                                    'bahan_baku'  => 'bg-emerald-100 text-emerald-700',
                                    'operasional' => 'bg-blue-100 text-blue-700',
                                    'gaji'        => 'bg-purple-100 text-purple-700',
                                    'perawatan'   => 'bg-amber-100 text-amber-700',
                                    'marketing'   => 'bg-orange-100 text-orange-700',
                                    'lainnya'     => 'bg-zinc-100 text-zinc-700',
                                ][$expense->category] ?? 'bg-zinc-100 text-zinc-700';
                            @endphp
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $catBadge }}">
                                {{ ucwords(str_replace('_', ' ', $expense->category)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-zinc-800 dark:text-zinc-200">{{ $expense->description }}</td>
                        <td class="px-5 py-3.5 text-right text-sm font-bold text-red-600">
                            Rp {{ number_format($expense->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    wire:click="openEdit({{ $expense->id }})"
                                    class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-600 transition hover:border-zinc-300 hover:bg-zinc-50 active:scale-95 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300"
                                >
                                    ✏️ Edit
                                </button>
                                <button
                                    wire:click="confirmDelete({{ $expense->id }})"
                                    class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-100 active:scale-95"
                                >
                                    🗑️ Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <span class="text-5xl">📋</span>
                                <p class="font-semibold text-zinc-500">Belum ada catatan pengeluaran</p>
                                <p class="text-sm text-zinc-400">Klik tombol "Catat Pengeluaran" untuk menambahkan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($expenses->hasPages())
            <div class="border-t border-zinc-100 px-5 py-4 dark:border-zinc-800">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>


    {{-- ═══════════════════════════════════════════════════════════════════
         MODAL: CREATE / EDIT FORM
    ═══════════════════════════════════════════════════════════════════ --}}
    <div
        x-data="{ open: @entangle('showForm') }"
        x-show="open"
        x-on:keydown.escape.window="$wire.closeForm()"
        class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center"
        style="display: none;"
    >
        {{-- Backdrop --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm"
            x-on:click="$wire.closeForm()"
        ></div>

        {{-- Panel --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
            class="relative z-50 w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-zinc-900"
        >
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-5 dark:border-zinc-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-xl">
                        {{ $editingId ? '✏️' : '➕' }}
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
                        {{ $editingId ? 'Edit Pengeluaran' : 'Catat Pengeluaran Baru' }}
                    </h3>
                </div>
                <button
                    wire:click="closeForm"
                    class="flex h-8 w-8 items-center justify-center rounded-full text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-800"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="space-y-4 px-6 py-5">
                {{-- Tanggal --}}
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        wire:model="formDate"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm text-zinc-700 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
                    />
                    @error('formDate') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2 sm:grid-cols-5">
                        @php
                            $catEmojis = ['bahan_baku' => '🥦', 'operasional' => '⚡', 'gaji' => '👤', 'perawatan' => '🔧', 'marketing' => '📢', 'lainnya' => '📋'];
                        @endphp
                        @foreach(\App\Livewire\Pengeluaran\Index::CATEGORIES as $cat)
                            <label
                                class="flex cursor-pointer flex-col items-center gap-1 rounded-xl border-2 p-2 text-center transition {{ $formCategory === $cat ? 'border-orange-400 bg-orange-50' : 'border-zinc-200 hover:border-zinc-300' }}"
                            >
                                <input type="radio" wire:model.live="formCategory" value="{{ $cat }}" class="sr-only">
                                <span class="text-xl">{{ $catEmojis[$cat] }}</span>
                                <span class="text-[10px] font-semibold {{ $formCategory === $cat ? 'text-orange-700' : 'text-zinc-500' }}">{{ ucwords(str_replace('_', ' ', $cat)) }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('formCategory') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="formDescription"
                        placeholder="Contoh: Bayar listrik bulan Juni"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm text-zinc-700 placeholder-zinc-400 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
                    />
                    @error('formDescription') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Jumlah (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-medium text-zinc-500">Rp</span>
                        <input
                            type="number"
                            wire:model="formAmount"
                            placeholder="0"
                            min="1"
                            class="w-full rounded-xl border border-zinc-300 py-2.5 pl-10 pr-4 text-sm text-zinc-700 placeholder-zinc-400 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
                        />
                    </div>
                    @error('formAmount') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex gap-3 border-t border-zinc-100 px-6 py-4 dark:border-zinc-800">
                <button
                    wire:click="closeForm"
                    class="flex-1 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-600 transition hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300"
                >
                    Batal
                </button>
                <button
                    wire:click="saveExpense"
                    wire:loading.attr="disabled"
                    wire:target="saveExpense"
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-600 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="saveExpense">
                        {{ $editingId ? '💾 Simpan Perubahan' : '✅ Simpan' }}
                    </span>
                    <span wire:loading wire:target="saveExpense" class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════════
         MODAL: DELETE CONFIRMATION
    ═══════════════════════════════════════════════════════════════════ --}}
    <div
        x-data
        x-show="$wire.deletingId !== null"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;"
    >
        <div
            x-show="$wire.deletingId !== null"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm"
            x-on:click="$wire.cancelDelete()"
        ></div>

        <div
            x-show="$wire.deletingId !== null"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="relative z-50 w-full max-w-sm overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-zinc-900"
        >
            <div class="p-6 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                    <span class="text-3xl">🗑️</span>
                </div>
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Hapus Pengeluaran?</h3>
                <p class="mt-2 text-sm text-zinc-500">Tindakan ini tidak dapat dibatalkan. Data pengeluaran akan dihapus permanen.</p>
            </div>
            <div class="flex gap-3 border-t border-zinc-100 px-6 py-4 dark:border-zinc-800">
                <button
                    wire:click="cancelDelete"
                    class="flex-1 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-600 transition hover:bg-zinc-50"
                >
                    Batal
                </button>
                <button
                    wire:click="delete"
                    wire:loading.attr="disabled"
                    wire:target="delete"
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-red-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-600 disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="delete">Ya, Hapus</span>
                    <span wire:loading wire:target="delete" class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        Menghapus...
                    </span>
                </button>
            </div>
        </div>
    </div>

</div>
