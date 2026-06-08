<div wire:poll="2s" class="flex h-full w-full flex-1 flex-col gap-6 p-6 bg-surface-container-low text-on-surface font-body-md antialiased">

    {{-- ── TopBar ────────────────────────────────────────────────── --}}
    <div class="bg-surface dark:bg-inverse-surface border-b border-outline-variant shadow-sm flex flex-col md:flex-row justify-between items-center w-full px-container-padding py-4 rounded-xl">
        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-start">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-3xl">restaurant</span>
                <div>
                    <h1 class="text-headline-lg font-headline-lg font-bold text-on-surface uppercase">DAPUR — GEPREK REJO</h1>
                    <p class="text-body-md font-body-md text-on-surface-variant">Kitchen Display System</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4 mt-4 md:mt-0 w-full md:w-auto justify-end">
            <div class="text-right">
                <div class="text-headline-md font-headline-md text-primary font-bold" id="kds-time">--:--:--</div>
                <div class="text-label-caps font-label-caps text-on-surface-variant" id="kds-date">-</div>
            </div>
            <button wire:click="logout" class="bg-error hover:bg-error-container text-white hover:text-on-error-container p-2 rounded-lg transition-colors flex items-center justify-center shadow-sm" title="Keluar">
                <span class="material-symbols-outlined">logout</span>
            </button>
        </div>
    </div>

    {{-- ── 2-Column KDS Grid ─────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col md:flex-row gap-6 md:gap-8 overflow-auto">

        {{-- Column 1: Antrian Masak --}}
        <section class="flex-1 flex flex-col gap-4">
            <div class="bg-surface rounded-xl shadow-sm border-t-4 border-t-primary p-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-secondary-container"></div>
                    <h2 class="text-headline-md font-headline-md font-bold uppercase tracking-wide">Antrian Masak</h2>
                </div>
                <span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-label-caps font-label-caps font-bold">
                    {{ $antrianCount ?? 0 }} pesanan
                </span>
            </div>

            @forelse($antrianMasak ?? [] as $order)
                <div class="bg-surface rounded-xl shadow-sm border border-surface-variant overflow-hidden flex flex-col">
                    <div class="bg-surface-container-low p-4 flex justify-between items-start border-b border-surface-variant">
                        <div>
                            <div class="text-order-number font-order-number text-primary leading-none">#{{ $order->order_number }}</div>
                            <div class="text-label-caps font-label-caps text-on-surface-variant mt-1">{{ $order->order_code }}</div>
                            <div class="mt-2 inline-flex items-center gap-1 bg-surface-variant text-on-surface-variant px-2 py-0.5 rounded text-xs font-medium">
                                <span class="material-symbols-outlined text-[14px]">{{ $order->type === 'dine_in' ? 'restaurant' : 'local_mall' }}</span>
                                {{ $order->type === 'dine_in' ? 'Dine In' : 'Take Away' }}
                            </div>
                        </div>
                        <div class="text-right bg-surface px-3 py-2 rounded-lg border border-surface-variant shadow-sm">
                            <div class="flex items-center gap-1 text-secondary font-bold text-sm">
                                <span class="material-symbols-outlined text-[16px]">schedule</span> Menunggu
                            </div>
                            <div class="text-body-lg font-body-lg font-bold text-primary">{{ $order->waiting_time }}</div>
                            <div class="text-xs text-on-surface-variant mt-0.5">sejak {{ $order->created_at_formatted }}</div>
                        </div>
                    </div>
                    <div class="p-4 flex-1">
                        @foreach($order->items as $item)
                            <div class="flex justify-between items-center bg-surface-container-lowest p-3 rounded-lg border border-surface-variant mb-2">
                                <span class="font-body-lg text-body-lg font-semibold text-on-surface">{{ $item->menu_name }}</span>
                                <span class="bg-secondary-container text-on-secondary-container px-2 py-1 rounded text-label-caps font-label-caps font-bold">x{{ $item->quantity }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-4 pt-0">
                        <button wire:click="mulaiMasak({{ $order->id }})"
                                class="w-full bg-primary hover:bg-surface-tint text-on-primary py-3 rounded-lg font-body-lg text-body-lg font-bold flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.98]">
                            <span class="material-symbols-outlined icon-fill">play_arrow</span>
                            Mulai Masak
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-surface rounded-xl border border-dashed border-surface-variant flex-1 flex flex-col items-center justify-center p-8 text-center min-h-[200px]">
                    <span class="material-symbols-outlined text-4xl text-tertiary-fixed-dim mb-3">restaurant</span>
                    <p class="font-body-md text-body-md text-on-surface-variant">Tidak ada antrian masak saat ini.</p>
                </div>
            @endforelse
        </section>

        {{-- Column 2: Sedang Dimasak --}}
        <section class="flex-1 flex flex-col gap-4">
            <div class="bg-surface rounded-xl shadow-sm border-t-4 border-t-secondary-container p-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                    <h2 class="text-headline-md font-headline-md font-bold uppercase tracking-wide">Sedang Dimasak</h2>
                </div>
                <span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-label-caps font-label-caps font-bold">
                    {{ $masakCount ?? 0 }} pesanan
                </span>
            </div>

            @forelse($sedangDimasak ?? [] as $order)
                <div class="bg-surface rounded-xl shadow-sm border border-surface-variant overflow-hidden flex flex-col">
                    <div class="bg-surface-container-low p-4 flex justify-between items-start border-b border-surface-variant">
                        <div>
                            <div class="text-order-number font-order-number text-primary leading-none">#{{ $order->order_number }}</div>
                            <div class="text-label-caps font-label-caps text-on-surface-variant mt-1">{{ $order->order_code }}</div>
                            <div class="mt-2 inline-flex items-center gap-1 bg-surface-variant text-on-surface-variant px-2 py-0.5 rounded text-xs font-medium">
                                <span class="material-symbols-outlined text-[14px]">{{ $order->type === 'dine_in' ? 'restaurant' : 'local_mall' }}</span>
                                {{ $order->type === 'dine_in' ? 'Dine In' : 'Take Away' }}
                            </div>
                        </div>
                        <div class="text-right bg-surface px-3 py-2 rounded-lg border border-surface-variant shadow-sm">
                            <div class="flex items-center gap-1 text-blue-600 font-bold text-sm">
                                <span class="material-symbols-outlined icon-fill text-[16px]">outdoor_grill</span> Dimasak
                            </div>
                            <div class="text-body-lg font-body-lg font-bold text-on-surface">{{ $order->cooking_time }}</div>
                            <div class="text-xs text-on-surface-variant mt-0.5">sejak {{ $order->started_at_formatted }}</div>
                        </div>
                    </div>
                    <div class="p-4 flex-1">
                        @foreach($order->items as $item)
                            <div class="flex justify-between items-center bg-surface-container-lowest p-3 rounded-lg border border-surface-variant mb-2">
                                <span class="font-body-lg text-body-lg font-semibold text-on-surface">{{ $item->menu_name }}</span>
                                <span class="bg-secondary-container text-on-secondary-container px-2 py-1 rounded text-label-caps font-label-caps font-bold">x{{ $item->quantity }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-4 pt-0">
                        <button wire:click="selesaiMasak({{ $order->id }})"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-body-lg text-body-lg font-bold flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.98]">
                            <span class="material-symbols-outlined icon-fill">check_circle</span>
                            Selesai
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-surface rounded-xl border border-dashed border-surface-variant flex-1 flex flex-col items-center justify-center p-8 text-center min-h-[300px]">
                    <div class="w-24 h-24 rounded-full bg-surface-container-low flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-4xl text-tertiary-fixed-dim">outdoor_grill</span>
                    </div>
                    <h3 class="text-headline-md font-headline-md font-semibold text-on-surface mb-2">Tidak ada yang dimasak</h3>
                    <p class="text-body-md font-body-md text-on-surface-variant max-w-xs">Tekan "Mulai Masak" dari kolom antrian untuk memulai pesanan baru.</p>
                </div>
            @endforelse
        </section>
    </div>

    @push('scripts')
    <script>
        // Live clock for KDS
        function updateKdsClock() {
            const now = new Date();
            const timeEl = document.getElementById('kds-time');
            const dateEl = document.getElementById('kds-date');
            if (timeEl) {
                timeEl.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
            if (dateEl) {
                const opts = { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' };
                dateEl.textContent = now.toLocaleDateString('id-ID', opts);
            }
        }
        updateKdsClock();
        setInterval(updateKdsClock, 1000);
    </script>
    @endpush
</div>
