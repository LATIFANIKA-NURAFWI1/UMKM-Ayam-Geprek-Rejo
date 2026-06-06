<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

    {{-- ═══════════════════════════════════════════════════════════════════
         HEADER
    ═══════════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Laporan Laba-Rugi</flux:heading>
            <flux:text class="mt-1">Analisis keuangan komprehensif: Revenue, HPP, Pengeluaran, dan Net Profit</flux:text>
        </div>
        <button wire:click="exportPdf"
            class="flex items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-red-700 active:scale-95">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export PDF
        </button>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         PERIOD FILTER
    ═══════════════════════════════════════════════════════════════════ --}}
    <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        {{-- Preset Buttons --}}
        <div class="mb-4 flex flex-wrap gap-2">
            @php
                $presets = [
                    'hari_ini'   => '📅 Hari Ini',
                    'minggu_ini' => '📆 Minggu Ini',
                    'bulan_ini'  => '🗓 Bulan Ini',
                    'bulan_lalu' => '⏮ Bulan Lalu',
                    'tahun_ini'  => '📊 Tahun Ini',
                ];
            @endphp
            @foreach($presets as $key => $label)
                <button
                    wire:click="applyPreset('{{ $key }}')"
                    @class([
                        'rounded-xl px-4 py-2 text-sm font-semibold transition',
                        'bg-orange-500 text-white shadow-sm' => $preset === $key,
                        'border border-zinc-200 bg-white text-zinc-600 hover:border-orange-300 hover:text-orange-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300' => $preset !== $key,
                    ])
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Date Range --}}
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label class="mb-1 block text-xs font-semibold text-zinc-500">Dari Tanggal</label>
                <input type="date" wire:model.live="dari"
                    class="rounded-xl border border-zinc-300 px-4 py-2.5 text-sm text-zinc-700 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
                />
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-zinc-500">Sampai Tanggal</label>
                <input type="date" wire:model.live="sampai"
                    class="rounded-xl border border-zinc-300 px-4 py-2.5 text-sm text-zinc-700 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
                />
            </div>
        </div>
    </div>

    @if(!empty($this->report))
        @php $r = $this->report; @endphp

        {{-- ═══════════════════════════════════════════════════════════════════
             KPI CARDS
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">

            {{-- Revenue --}}
            <div class="flex flex-col gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-800/40 dark:bg-emerald-900/20">
                <div class="flex items-center gap-2">
                    <span class="text-xl">💰</span>
                    <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">Total Revenue</p>
                </div>
                <p class="text-2xl font-black text-emerald-800 dark:text-emerald-300">
                    Rp {{ number_format($r['revenue'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-emerald-600">{{ $r['order_count'] }} pesanan</p>
            </div>

            {{-- HPP --}}
            <div class="flex flex-col gap-2 rounded-2xl border border-red-200 bg-red-50 p-5 dark:border-red-800/40 dark:bg-red-900/20">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🥩</span>
                    <p class="text-xs font-semibold text-red-700 dark:text-red-400">Total HPP</p>
                </div>
                <p class="text-2xl font-black text-red-800 dark:text-red-300">
                    Rp {{ number_format($r['total_hpp'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-red-600">Harga pokok bahan baku</p>
            </div>

            {{-- Gross Profit --}}
            <div class="flex flex-col gap-2 rounded-2xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-800/40 dark:bg-blue-900/20">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📈</span>
                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-400">Laba Kotor</p>
                </div>
                <p class="text-2xl font-black text-blue-800 dark:text-blue-300">
                    Rp {{ number_format($r['gross_profit'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-blue-600">Margin {{ $r['gross_margin_pct'] }}%</p>
            </div>

            {{-- Total Expense --}}
            <div class="flex flex-col gap-2 rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-800/40 dark:bg-amber-900/20">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📤</span>
                    <p class="text-xs font-semibold text-amber-700 dark:text-amber-400">Total Expense</p>
                </div>
                <p class="text-2xl font-black text-amber-800 dark:text-amber-300">
                    Rp {{ number_format($r['total_expenses'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-amber-600">Biaya operasional</p>
            </div>

            {{-- Net Profit --}}
            @php $netPositive = $r['net_profit'] >= 0; @endphp
            <div @class([
                'flex flex-col gap-2 rounded-2xl border p-5',
                'border-emerald-300 bg-emerald-500 text-white' => $netPositive,
                'border-red-300 bg-red-500 text-white' => !$netPositive,
            ])>
                <div class="flex items-center gap-2">
                    <span class="text-xl">{{ $netPositive ? '✅' : '❌' }}</span>
                    <p class="text-xs font-semibold opacity-80">{{ $netPositive ? 'Laba Bersih' : 'Rugi Bersih' }}</p>
                </div>
                <p class="text-2xl font-black">
                    Rp {{ number_format(abs($r['net_profit']), 0, ',', '.') }}
                </p>
                <p class="text-xs opacity-70">Net margin {{ $r['net_margin_pct'] }}%</p>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             TWO COLUMNS: Income Statement + Expense Breakdown
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="grid gap-4 lg:grid-cols-2">

            {{-- Income Statement --}}
            <div class="rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <h3 class="font-bold text-zinc-800 dark:text-white">📋 Laporan Laba-Rugi</h3>
                    <p class="text-xs text-zinc-400 mt-0.5">
                        {{ \Carbon\Carbon::parse($r['period_from'])->translatedFormat('d M Y') }}
                        —
                        {{ \Carbon\Carbon::parse($r['period_to'])->translatedFormat('d M Y') }}
                    </p>
                </div>
                <div class="p-5 space-y-1">
                    @foreach($r['summary'] as $row)
                        <div @class([
                            'flex items-center justify-between rounded-xl px-4 py-3',
                            'bg-emerald-50' => $row['type'] === 'income',
                            'bg-red-50' => $row['type'] === 'expense',
                            'bg-blue-50 font-bold' => $row['type'] === 'subtotal',
                            'bg-zinc-900 text-white' => $row['type'] === 'total',
                        ])>
                            <span @class([
                                'text-sm',
                                'text-emerald-800' => $row['type'] === 'income',
                                'text-red-800' => $row['type'] === 'expense',
                                'text-blue-800 font-semibold' => $row['type'] === 'subtotal',
                                'text-white font-bold' => $row['type'] === 'total',
                            ])>
                                {{ $row['label'] }}
                            </span>
                            <span @class([
                                'text-sm font-bold tabular-nums',
                                'text-emerald-700' => $row['type'] === 'income',
                                'text-red-700' => $row['type'] === 'expense',
                                'text-blue-700' => $row['type'] === 'subtotal',
                                'text-white text-base' => $row['type'] === 'total',
                            ])>
                                @if($row['amount'] < 0)
                                    − Rp {{ number_format(abs($row['amount']), 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($row['amount'], 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Expense Breakdown --}}
            <div class="rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <h3 class="font-bold text-zinc-800 dark:text-white">📊 Rincian Pengeluaran</h3>
                </div>
                <div class="p-5 space-y-3">
                    @if(empty($r['expenses_by_category']))
                        <div class="flex flex-col items-center py-8 text-center">
                            <span class="text-4xl">📭</span>
                            <p class="mt-3 text-sm text-zinc-400">Tidak ada pengeluaran pada periode ini</p>
                        </div>
                    @else
                        @php $totalExp = $r['total_expenses']; @endphp
                        @foreach($r['expenses_by_category'] as $cat => $amount)
                            @php $pct = $totalExp > 0 ? round($amount / $totalExp * 100, 1) : 0; @endphp
                            <div>
                                <div class="mb-1 flex items-center justify-between">
                                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $cat }}</span>
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white">
                                        Rp {{ number_format($amount, 0, ',', '.') }}
                                        <span class="ml-1 text-xs font-normal text-zinc-400">({{ $pct }}%)</span>
                                    </span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                                    <div
                                        class="h-full rounded-full bg-amber-400 transition-all duration-500"
                                        style="width: {{ $pct }}%"
                                    ></div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             TOP MENU ITEMS
        ═══════════════════════════════════════════════════════════════════ --}}
        @if(!empty($this->topItems))
            <div class="rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <h3 class="font-bold text-zinc-800 dark:text-white">🏆 Top 10 Menu Terlaris</h3>
                    <p class="text-xs text-zinc-400 mt-0.5">Berdasarkan revenue tertinggi</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">#</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Menu</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Qty</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Revenue</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">HPP</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Laba</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Margin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach($this->topItems as $i => $item)
                                <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                                    <td class="px-5 py-3 text-sm font-bold text-zinc-400">{{ $i + 1 }}</td>
                                    <td class="px-5 py-3 text-sm font-semibold text-zinc-800 dark:text-zinc-200">
                                        {{ $item['menu_item_name'] }}
                                    </td>
                                    <td class="px-5 py-3 text-right text-sm text-zinc-600">{{ $item['total_qty'] }}x</td>
                                    <td class="px-5 py-3 text-right text-sm font-medium text-emerald-700">
                                        Rp {{ number_format($item['total_revenue'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3 text-right text-sm text-red-600">
                                        Rp {{ number_format($item['total_hpp'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3 text-right text-sm font-bold text-blue-700">
                                        Rp {{ number_format($item['gross_profit'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <span @class([
                                            'inline-block rounded-full px-2.5 py-0.5 text-xs font-bold',
                                            'bg-emerald-100 text-emerald-700' => $item['margin_pct'] >= 30,
                                            'bg-amber-100 text-amber-700' => $item['margin_pct'] >= 15 && $item['margin_pct'] < 30,
                                            'bg-red-100 text-red-700' => $item['margin_pct'] < 15,
                                        ])>
                                            {{ $item['margin_pct'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════════════════════
             DAILY TREND TABLE
        ═══════════════════════════════════════════════════════════════════ --}}
        @if(!empty($this->dailyTrend))
            <div class="rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <h3 class="font-bold text-zinc-800 dark:text-white">📅 Tren Harian</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Tanggal</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Revenue</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">HPP</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Laba Kotor</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Expense</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Net Profit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach($this->dailyTrend as $day)
                                @php $isProfit = $day['net_profit'] >= 0; @endphp
                                <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                                    <td class="px-5 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                        {{ \Carbon\Carbon::parse($day['date'])->translatedFormat('d M Y, l') }}
                                    </td>
                                    <td class="px-5 py-3 text-right text-sm text-emerald-700">Rp {{ number_format($day['revenue'], 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right text-sm text-red-600">Rp {{ number_format($day['hpp'], 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right text-sm font-medium text-blue-600">Rp {{ number_format($day['gross_profit'], 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right text-sm text-amber-700">Rp {{ number_format($day['expenses'], 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <span @class([
                                            'text-sm font-bold',
                                            'text-emerald-700' => $isProfit,
                                            'text-red-600' => !$isProfit,
                                        ])>
                                            {{ $isProfit ? '+' : '-' }}Rp {{ number_format(abs($day['net_profit']), 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    @else
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-zinc-300 bg-white py-20 dark:border-zinc-700 dark:bg-zinc-900">
            <span class="text-6xl">📊</span>
            <p class="mt-4 text-lg font-semibold text-zinc-500">Pilih periode untuk melihat laporan</p>
            <p class="mt-1 text-sm text-zinc-400">Gunakan tombol preset atau isi tanggal secara manual</p>
        </div>
    @endif

</div>
