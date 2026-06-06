<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <flux:heading size="xl" level="1">Pesanan</flux:heading>
            <flux:text class="mt-1">Riwayat transaksi penjualan</flux:text>
        </div>
        {{-- Mode Toggle --}}
        <div class="flex items-center gap-1 rounded-xl border border-zinc-200 bg-white p-1 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <button wire:click="$set('viewMode', 'harian')"
                @class([
                    'rounded-lg px-4 py-2 text-sm font-semibold transition',
                    'bg-orange-500 text-white shadow-sm' => $viewMode === 'harian',
                    'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400' => $viewMode !== 'harian',
                ])>
                📅 Harian
            </button>
            <button wire:click="$set('viewMode', 'bulanan')"
                @class([
                    'rounded-lg px-4 py-2 text-sm font-semibold transition',
                    'bg-orange-500 text-white shadow-sm' => $viewMode === 'bulanan',
                    'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400' => $viewMode !== 'bulanan',
                ])>
                🗓 Bulanan
            </button>
        </div>
    </div>

    {{-- =====================================================================
         MODE BULANAN: Summary per hari dalam 1 bulan
         ===================================================================== --}}
    @if($viewMode === 'bulanan')

        {{-- Filter Bulan --}}
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label class="mb-1 block text-xs font-semibold text-zinc-500">Pilih Bulan</label>
                <input type="month" wire:model.live="bulan"
                    class="rounded-xl border border-zinc-300 px-4 py-2.5 text-sm text-zinc-700 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200">
            </div>
        </div>

        @if(!empty($this->dailySummary))
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                <div class="flex flex-col gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-800/40 dark:bg-emerald-900/20">
                    <p class="text-xs font-semibold text-emerald-700">💰 Total Pendapatan</p>
                    <p class="text-2xl font-black text-emerald-800">
                        Rp {{ number_format($this->totalRevenueBulan, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-emerald-600">
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}
                    </p>
                </div>
                <div class="flex flex-col gap-2 rounded-2xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-800/40 dark:bg-blue-900/20">
                    <p class="text-xs font-semibold text-blue-700">🛒 Total Transaksi</p>
                    <p class="text-2xl font-black text-blue-800">{{ number_format($this->totalPesananBulan) }}</p>
                    <p class="text-xs text-blue-600">pesanan masuk</p>
                </div>
                @php
                    $hariAda = count($this->dailySummary);
                    $rataHarian = $hariAda > 0 ? $this->totalRevenueBulan / $hariAda : 0;
                @endphp
                <div class="col-span-2 flex flex-col gap-2 rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 sm:col-span-1">
                    <p class="text-xs font-semibold text-zinc-500">📊 Rata-rata Harian</p>
                    <p class="text-2xl font-black text-zinc-900 dark:text-white">
                        Rp {{ number_format($rataHarian, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-zinc-400">dari {{ $hariAda }} hari operasional</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <h3 class="font-bold text-zinc-800 dark:text-white">
                        📋 Rincian Harian —
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}
                    </h3>
                </div>
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Tanggal</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Pesanan</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Terbayar</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach($this->dailySummary as $day)
                            <tr class="cursor-pointer transition hover:bg-orange-50 dark:hover:bg-zinc-800/50"
                                wire:click="$set('viewMode', 'harian'); $set('tanggal', '{{ $day['tanggal'] }}')">
                                <td class="px-5 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                    {{ \Carbon\Carbon::parse($day['tanggal'])->translatedFormat('d F Y, l') }}
                                </td>
                                <td class="px-5 py-3 text-right text-sm text-zinc-600">{{ $day['total_pesanan'] }}</td>
                                <td class="px-5 py-3 text-right text-sm text-zinc-500">{{ $day['terbayar'] }}</td>
                                <td class="px-5 py-3 text-right font-bold text-emerald-700">
                                    Rp {{ number_format($day['revenue'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="bg-zinc-900 dark:bg-zinc-800">
                            <td class="px-5 py-3 text-sm font-bold text-white">Total Bulan Ini</td>
                            <td class="px-5 py-3 text-right text-sm text-zinc-300">{{ $this->totalPesananBulan }}</td>
                            <td class="px-5 py-3 text-right text-sm text-zinc-300">—</td>
                            <td class="px-5 py-3 text-right text-base font-black text-emerald-400">
                                Rp {{ number_format($this->totalRevenueBulan, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-zinc-300 py-16 dark:border-zinc-700">
                <span class="text-5xl">📭</span>
                <p class="mt-3 text-sm text-zinc-400">Tidak ada transaksi pada bulan ini</p>
            </div>
        @endif

    @else

    {{-- =====================================================================
         MODE HARIAN: List transaksi
         ===================================================================== --}}
        <div class="flex flex-wrap gap-3">
            <input type="date" wire:model.live="tanggal"
                class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-700 shadow-sm focus:border-orange-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 sm:w-44">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" placeholder="Cari no. pesanan…"
                    class="rounded-xl border border-zinc-300 bg-white py-2.5 pl-10 pr-4 text-sm text-zinc-700 shadow-sm focus:border-orange-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 sm:w-56">
            </div>
        </div>

        {{-- Summary harian --}}
        @if($tanggal)
            @php
                $ordersToday = $orders->getCollection();
                $revenueHarian = $ordersToday->whereIn('status', ['confirmed','preparing','completed'])->sum('total_amount');
            @endphp
            <div class="flex flex-wrap gap-3">
                <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3 dark:border-emerald-800/40 dark:bg-emerald-900/20">
                    <span class="text-xl">💰</span>
                    <div>
                        <p class="text-xs font-semibold text-emerald-700">Pendapatan</p>
                        <p class="text-lg font-black text-emerald-800">Rp {{ number_format($revenueHarian, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-zinc-200 bg-white px-5 py-3 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <span class="text-xl">🛒</span>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Total Pesanan</p>
                        <p class="text-lg font-black text-zinc-900 dark:text-white">{{ $orders->total() }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-zinc-100 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800">
                    <span class="text-lg">📅</span>
                    <p class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">
                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>
        @endif

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">No. Pesanan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Pelanggan / Meja</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Item</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($orders as $order)
                        @php
                            $badge = match($order->status) {
                                'pending'   => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400',
                                'confirmed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                                'preparing' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-400',
                                'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400',
                                default     => 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400',
                            };
                            $label = match($order->status) {
                                'pending'   => 'Menunggu',
                                'confirmed' => 'Dikonfirmasi',
                                'preparing' => 'Dimasak',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                default     => $order->status,
                            };
                        @endphp
                        <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-3">
                                <span class="font-mono text-sm font-semibold text-zinc-900 dark:text-white">#{{ $order->order_number }}</span>
                                @if($order->queue_number)
                                    <span class="ml-1 text-xs text-zinc-400">Antrian {{ $order->queue_number }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                @if($order->member)
                                    <span class="inline-flex items-center gap-1">
                                        <span class="text-amber-500">⭐</span>
                                        <span class="font-medium">{{ $order->member->name }}</span>
                                    </span>
                                @else
                                    <span class="text-zinc-400">Pelanggan</span>
                                @endif
                                <div class="text-xs text-zinc-400 mt-0.5">
                                    {{ $order->table_number ? 'Meja '.$order->table_number : '-' }}
                                    <span class="ml-1 rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-700">
                                        {{ $order->type === 'dine_in' ? 'Dine In' : 'Take Away' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-zinc-500">{{ $order->details->count() }} item</td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-zinc-900 dark:text-white">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badge }}">{{ $label }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-zinc-400">{{ $order->created_at->format('H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-sm text-zinc-400">
                                Tidak ada pesanan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $orders->links() }}</div>

    @endif

</div>
