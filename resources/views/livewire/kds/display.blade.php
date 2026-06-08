<div wire:poll.5s.keep-alive class="flex flex-col h-screen w-full bg-[#f4f6f9] dark:bg-gray-950 text-gray-800 dark:text-gray-100 font-sans antialiased overflow-hidden">
    
    {{-- ── TopBar ────────────────────────────────────────────────── --}}
    <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center w-full px-6 py-4 shadow-sm z-10 shrink-0">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Ayam Geprek Rejo" class="h-12 w-auto object-contain">
            <div>
                <h1 class="text-md md:text-lg font-extrabold text-gray-800 dark:text-gray-100 tracking-wider leading-tight">DAPUR — GEPREK REJO</h1>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-widest">Kitchen Display System</p>
            </div>
        </div>

        {{-- Clock, Theme Toggle, & Logout --}}
        <div class="flex items-center gap-4 md:gap-6">
            <div class="text-right hidden sm:block" wire:ignore>
                <div class="text-xl md:text-2xl font-black text-[#bc000a] dark:text-red-500 tracking-wider leading-none font-mono" id="kds-time">--.--.--</div>
                <div class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold mt-1 uppercase tracking-wide" id="kds-date">-</div>
            </div>

            {{-- Dark Mode Toggle --}}
            <button @click="toggle()" class="w-10 h-10 rounded-full border border-gray-300 dark:border-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors shadow-xs cursor-pointer" title="Ubah Tema">
                <span class="material-symbols-outlined" x-text="isDark ? 'light_mode' : 'dark_mode'">dark_mode</span>
            </button>

            {{-- Logout Button --}}
            <button wire:click="logout" class="border-2 border-[#bc000a] hover:bg-red-50 dark:hover:bg-red-950/20 text-[#bc000a] dark:text-red-500 px-4 py-2 rounded-xl transition-all font-bold text-xs flex items-center gap-2 shadow-xs cursor-pointer" title="Keluar">
                <span class="material-symbols-outlined text-sm">logout</span>
                <span class="hidden sm:inline">Keluar</span>
            </button>
        </div>
    </header>

    {{-- ── Main Scrollable Canvas ───────────────────────────────── --}}
    <main class="flex-1 overflow-y-auto p-4 md:p-6">
        
        {{-- TAB 1: ANTRIAN --}}
        @if($activeTab === 'antrian')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                
                {{-- Column 1: Antrian Masak --}}
                <section class="flex flex-col gap-4">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border-t-4 border-[#bc000a] px-4 py-3 flex justify-between items-center border border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 animate-pulse"></span>
                            <h2 class="text-sm font-extrabold uppercase tracking-widest text-gray-700 dark:text-gray-300">Antrian Masak</h2>
                        </div>
                        <span class="bg-[#fabd00] text-[#5b4300] px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-xs">
                            {{ $antrianCount }} pesanan
                        </span>
                    </div>

                    <div class="flex flex-col gap-4">
                        @forelse($antrianMasak as $order)
                            <div wire:key="antrian-{{ $order->id }}" class="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-200 dark:border-gray-800 overflow-hidden flex flex-col p-4 gap-3 transition-all hover:shadow-lg">
                                <div class="flex justify-between items-start gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-5xl font-black text-[#bc000a] dark:text-red-500 tracking-tighter leading-none">#{{ $order->queue_number }}</div>
                                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1.5 uppercase tracking-wide">{{ $order->order_number }}</div>
                                        <div class="mt-2.5 inline-flex items-center gap-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-xl text-xs font-bold">
                                            <span class="material-symbols-outlined text-[15px]">{{ $order->type === 'takeaway' ? 'local_mall' : 'restaurant' }}</span>
                                            <span>{{ $order->type === 'takeaway' ? 'Take Away' : 'Dine In' }}</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Waiting Time Widget --}}
                                    <div class="bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/30 px-3.5 py-2.5 rounded-2xl text-right flex flex-col items-end min-w-[115px] shrink-0">
                                        <div class="flex items-center gap-1 text-[#bc000a] dark:text-red-500 font-extrabold text-[10px] uppercase tracking-wider">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                                            <span>Menunggu</span>
                                        </div>
                                        <div class="text-xl font-black text-[#bc000a] dark:text-red-500 mt-0.5 leading-none">{{ $this->getWaitingTime($order) }}</div>
                                        <div class="text-[9px] font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase">sejak {{ $order->confirmed_at ? $order->confirmed_at->format('H:i') : '-' }}</div>
                                    </div>
                                </div>

                                {{-- Items List --}}
                                <div class="space-y-1.5 mt-1">
                                    @foreach($order->details as $detail)
                                        <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800 px-3.5 py-2.5 rounded-xl">
                                            <span class="font-bold text-gray-800 dark:text-gray-200 text-sm leading-tight">{{ $detail->menu_item_name }}</span>
                                            <span class="bg-[#fabd00] text-[#5b4300] px-2.5 py-1 rounded-lg text-xs font-black shrink-0 ml-2">x{{ $detail->quantity }}</span>
                                        </div>
                                        @if(filled($detail->notes))
                                            <div class="text-[11px] font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/20 px-3 py-1.5 rounded-lg border border-amber-100 dark:border-amber-900/30 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[13px]">edit_note</span>
                                                <span>{{ $detail->notes }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- Action Button --}}
                                <button wire:click="mulaiMasak({{ $order->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="mulaiMasak({{ $order->id }})"
                                        class="w-full bg-[#bc000a] hover:bg-[#a00008] disabled:opacity-70 text-white py-3 px-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.98] cursor-pointer mt-1">
                                    <span wire:loading.remove wire:target="mulaiMasak({{ $order->id }})" class="material-symbols-outlined icon-fill text-[18px]">play_arrow</span>
                                    <span wire:loading wire:target="mulaiMasak({{ $order->id }})" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                                    <span wire:loading.remove wire:target="mulaiMasak({{ $order->id }})">Mulai Masak</span>
                                    <span wire:loading wire:target="mulaiMasak({{ $order->id }})">Memproses...</span>
                                </button>
                            </div>
                        @empty
                            <div class="bg-white dark:bg-gray-900 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-800 flex flex-col items-center justify-center p-10 text-center">
                                <div class="w-14 h-14 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center mb-3">
                                    <span class="material-symbols-outlined text-3xl text-gray-300 dark:text-gray-600">restaurant</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Tidak ada antrian masak saat ini.</p>
                                <p class="text-xs text-gray-400 dark:text-gray-600 mt-1">Pesanan baru akan muncul otomatis</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                {{-- Column 2: Sedang Dimasak --}}
                <section class="flex flex-col gap-4">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border-t-4 border-blue-500 px-4 py-3 flex justify-between items-center border border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                            <h2 class="text-sm font-extrabold uppercase tracking-widest text-gray-700 dark:text-gray-300">Sedang Dimasak</h2>
                        </div>
                        <span class="bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">
                            {{ $masakCount }} pesanan
                        </span>
                    </div>

                    <div class="flex flex-col gap-4">
                        @forelse($sedangDimasak as $order)
                            <div wire:key="masak-{{ $order->id }}" class="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-200 dark:border-gray-800 overflow-hidden flex flex-col p-4 gap-3 transition-all hover:shadow-lg">
                                <div class="flex justify-between items-start gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-5xl font-black text-[#bc000a] dark:text-red-500 tracking-tighter leading-none">#{{ $order->queue_number }}</div>
                                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1.5 uppercase tracking-wide">{{ $order->order_number }}</div>
                                        <div class="mt-2.5 inline-flex items-center gap-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-xl text-xs font-bold">
                                            <span class="material-symbols-outlined text-[15px]">{{ $order->type === 'takeaway' ? 'local_mall' : 'restaurant' }}</span>
                                            <span>{{ $order->type === 'takeaway' ? 'Take Away' : 'Dine In' }}</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Cooking Time Widget --}}
                                    <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/30 px-3.5 py-2.5 rounded-2xl text-right flex flex-col items-end min-w-[115px] shrink-0">
                                        <div class="flex items-center gap-1 text-blue-600 dark:text-blue-400 font-extrabold text-[10px] uppercase tracking-wider">
                                            <span class="material-symbols-outlined text-[14px] icon-fill">outdoor_grill</span>
                                            <span>Dimasak</span>
                                        </div>
                                        <div class="text-xl font-black text-blue-700 dark:text-blue-400 mt-0.5 leading-none">{{ $this->getCookingTime($order) }}</div>
                                        <div class="text-[9px] font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase">sejak {{ $order->updated_at ? $order->updated_at->format('H:i') : '-' }}</div>
                                    </div>
                                </div>

                                {{-- Items List --}}
                                <div class="space-y-1.5 mt-1">
                                    @foreach($order->details as $detail)
                                        <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800 px-3.5 py-2.5 rounded-xl">
                                            <span class="font-bold text-gray-800 dark:text-gray-200 text-sm leading-tight">{{ $detail->menu_item_name }}</span>
                                            <span class="bg-[#fabd00] text-[#5b4300] px-2.5 py-1 rounded-lg text-xs font-black shrink-0 ml-2">x{{ $detail->quantity }}</span>
                                        </div>
                                        @if(filled($detail->notes))
                                            <div class="text-[11px] font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/20 px-3 py-1.5 rounded-lg border border-amber-100 dark:border-amber-900/30 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[13px]">edit_note</span>
                                                <span>{{ $detail->notes }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- Action Button --}}
                                <button wire:click="selesaiMasak({{ $order->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="selesaiMasak({{ $order->id }})"
                                        class="w-full bg-[#10b981] hover:bg-[#059669] disabled:opacity-70 text-white py-3 px-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.98] cursor-pointer mt-1">
                                    <span wire:loading.remove wire:target="selesaiMasak({{ $order->id }})" class="material-symbols-outlined icon-fill text-[18px]">check_circle</span>
                                    <span wire:loading wire:target="selesaiMasak({{ $order->id }})" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                                    <span wire:loading.remove wire:target="selesaiMasak({{ $order->id }})">Selesai</span>
                                    <span wire:loading wire:target="selesaiMasak({{ $order->id }})">Menyimpan...</span>
                                </button>
                            </div>
                        @empty
                            <div class="bg-white dark:bg-gray-900 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-800 flex flex-col items-center justify-center p-10 text-center">
                                <div class="w-14 h-14 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center mb-3">
                                    <span class="material-symbols-outlined text-3xl text-gray-300 dark:text-gray-600">outdoor_grill</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Tidak ada pesanan yang sedang dimasak.</p>
                                <p class="text-xs text-gray-400 dark:text-gray-600 mt-1">Tekan "Mulai Masak" dari antrian</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        @endif

        {{-- TAB 2: PROSES --}}
        @if($activeTab === 'proses')
            <div class="flex flex-col gap-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border-t-4 border-blue-500 px-4 py-3 flex justify-between items-center border border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                        <h2 class="text-sm font-extrabold uppercase tracking-widest text-gray-700 dark:text-gray-300">Fokus Sedang Dimasak</h2>
                    </div>
                    <span class="bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">
                        {{ $masakCount }} pesanan aktif
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($sedangDimasak as $order)
                        <div wire:key="proses-{{ $order->id }}" class="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-200 dark:border-gray-800 overflow-hidden flex flex-col p-4 gap-3">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="text-5xl font-black text-[#bc000a] dark:text-red-500 tracking-tighter leading-none">#{{ $order->queue_number }}</div>
                                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1.5 uppercase tracking-wide">{{ $order->order_number }}</div>
                                    <div class="mt-2.5 inline-flex items-center gap-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-xl text-xs font-bold">
                                        <span class="material-symbols-outlined text-[15px]">{{ $order->type === 'takeaway' ? 'local_mall' : 'restaurant' }}</span>
                                        <span>{{ $order->type === 'takeaway' ? 'Take Away' : 'Dine In' }}</span>
                                    </div>
                                </div>
                                
                                <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/30 px-3.5 py-2.5 rounded-2xl text-right flex flex-col items-end min-w-[110px] shrink-0">
                                    <div class="flex items-center gap-1 text-blue-600 dark:text-blue-400 font-extrabold text-[10px] uppercase tracking-wider">
                                        <span class="material-symbols-outlined text-[14px] icon-fill">outdoor_grill</span>
                                        <span>Dimasak</span>
                                    </div>
                                    <div class="text-xl font-black text-blue-700 dark:text-blue-400 mt-0.5 leading-none">{{ $this->getCookingTime($order) }}</div>
                                    <div class="text-[9px] font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase">sejak {{ $order->updated_at ? $order->updated_at->format('H:i') : '-' }}</div>
                                </div>
                            </div>

                            <div class="space-y-1.5 mt-1">
                                @foreach($order->details as $detail)
                                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800 px-3.5 py-2.5 rounded-xl">
                                        <span class="font-bold text-gray-800 dark:text-gray-200 text-sm leading-tight">{{ $detail->menu_item_name }}</span>
                                        <span class="bg-[#fabd00] text-[#5b4300] px-2.5 py-1 rounded-lg text-xs font-black shrink-0 ml-2">x{{ $detail->quantity }}</span>
                                    </div>
                                    @if(filled($detail->notes))
                                        <div class="text-[11px] font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/20 px-3 py-1.5 rounded-lg border border-amber-100 dark:border-amber-900/30 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[13px]">edit_note</span>
                                            <span>{{ $detail->notes }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <button wire:click="selesaiMasak({{ $order->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="selesaiMasak({{ $order->id }})"
                                    class="w-full bg-[#10b981] hover:bg-[#059669] disabled:opacity-70 text-white py-3 px-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.98] cursor-pointer mt-1">
                                <span wire:loading.remove wire:target="selesaiMasak({{ $order->id }})" class="material-symbols-outlined icon-fill text-[18px]">check_circle</span>
                                <span wire:loading wire:target="selesaiMasak({{ $order->id }})" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                                <span wire:loading.remove wire:target="selesaiMasak({{ $order->id }})">Selesai</span>
                                <span wire:loading wire:target="selesaiMasak({{ $order->id }})">Menyimpan...</span>
                            </button>
                        </div>
                    @empty
                        <div class="col-span-full bg-white dark:bg-gray-900 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-800 flex flex-col items-center justify-center p-12 text-center">
                            <div class="w-14 h-14 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center mb-3">
                                <span class="material-symbols-outlined text-3xl text-gray-300 dark:text-gray-600">outdoor_grill</span>
                            </div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Tidak ada pesanan yang sedang dimasak saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- TAB 3: RIWAYAT --}}
        @if($activeTab === 'riwayat')
            <div class="flex flex-col gap-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border-t-4 border-[#10b981] px-4 py-3 flex justify-between items-center border border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#10b981] text-xl icon-fill">task_alt</span>
                        <h2 class="text-sm font-extrabold uppercase tracking-widest text-gray-700 dark:text-gray-300">Riwayat Pesanan Hari Ini</h2>
                    </div>
                    <span class="bg-green-100 dark:bg-green-950/40 text-green-700 dark:text-green-400 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">
                        {{ $riwayatPesanan->count() }} selesai
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($riwayatPesanan as $order)
                        <div wire:key="riwayat-{{ $order->id }}" class="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-200 dark:border-gray-800/80 overflow-hidden flex flex-col p-4 gap-3 opacity-90 hover:opacity-100 transition-opacity">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="text-4xl font-extrabold text-gray-400 dark:text-gray-500 tracking-tighter leading-none">#{{ $order->queue_number }}</div>
                                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 mt-1 uppercase tracking-wide">{{ $order->order_number }}</div>
                                    <div class="mt-2 inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 px-2 py-0.5 rounded-lg text-[10px] font-bold border border-gray-100 dark:border-gray-700">
                                        <span class="material-symbols-outlined text-[13px]">{{ $order->type === 'takeaway' ? 'local_mall' : 'restaurant' }}</span>
                                        <span>{{ $order->type === 'takeaway' ? 'Take Away' : 'Dine In' }}</span>
                                    </div>
                                </div>
                                
                                <div class="bg-green-50 dark:bg-green-950/20 border border-green-100 dark:border-green-900/30 px-3 py-2 rounded-2xl text-right flex flex-col items-end min-w-[110px] shrink-0">
                                    <div class="flex items-center gap-1 text-green-700 dark:text-green-400 font-extrabold text-[9px] uppercase tracking-wider">
                                        <span class="material-symbols-outlined text-[13px] icon-fill">done_all</span>
                                        <span>Selesai</span>
                                    </div>
                                    <div class="text-lg font-black text-green-700 dark:text-green-400 mt-0.5 leading-none">{{ $order->completed_at ? $order->completed_at->format('H:i') : '-' }}</div>
                                    <div class="text-[9px] text-gray-500 dark:text-gray-400 mt-1 uppercase">dari {{ $order->confirmed_at ? $order->confirmed_at->format('H:i') : '-' }}</div>
                                </div>
                            </div>

                            <div class="space-y-1.5 mt-1 flex-1">
                                @foreach($order->details as $detail)
                                    <div class="flex justify-between items-center bg-gray-50/70 dark:bg-gray-950/50 border border-gray-100 dark:border-gray-800/50 px-3 py-2 rounded-xl">
                                        <span class="font-semibold text-gray-600 dark:text-gray-400 text-xs">{{ $detail->menu_item_name }}</span>
                                        <span class="bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-400 px-2 py-0.5 rounded text-[10px] font-extrabold ml-2">x{{ $detail->quantity }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white dark:bg-gray-900 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-800 flex flex-col items-center justify-center p-12 text-center">
                            <div class="w-14 h-14 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center mb-3">
                                <span class="material-symbols-outlined text-3xl text-gray-300 dark:text-gray-600">history</span>
                            </div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Belum ada pesanan yang selesai dimasak hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- TAB 4: MENU --}}
        @if($activeTab === 'menu')
            <div class="flex flex-col gap-6">
                
                {{-- Search & Title --}}
                <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#bc000a] text-xl icon-fill">restaurant_menu</span>
                        <h2 class="text-sm font-extrabold uppercase tracking-widest text-gray-700 dark:text-gray-300">Ketersediaan Menu</h2>
                    </div>

                    {{-- Search Input --}}
                    <div class="relative flex items-center bg-gray-50 dark:bg-gray-950 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 flex-1 sm:max-w-xs">
                        <span class="material-symbols-outlined text-gray-400 text-lg mr-2 shrink-0">search</span>
                        <input type="text" wire:model.live.debounce.250ms="searchQuery" placeholder="Cari nama menu..." class="w-full bg-transparent border-none outline-none text-xs text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:ring-0">
                        @if(filled($searchQuery))
                            <button wire:click="$set('searchQuery', '')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer ml-1 shrink-0">
                                <span class="material-symbols-outlined text-base">close</span>
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Categories & Items --}}
                <div class="space-y-6">
                    @forelse($menuItemsGroup as $categoryName => $items)
                        <div class="bg-white dark:bg-gray-900/60 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xs">
                            <h3 class="text-xs font-black text-gray-500 dark:text-gray-400 mb-4 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800 pb-2.5 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-[#bc000a]">label</span>
                                <span>{{ $categoryName }}</span>
                                <span class="ml-auto text-[10px] normal-case font-semibold bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 px-2 py-0.5 rounded-full">{{ count($items) }} menu</span>
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($items as $item)
                                    <div class="p-3.5 rounded-xl border {{ $item->is_available ? 'bg-white dark:bg-gray-950 border-gray-200 dark:border-gray-800' : 'bg-gray-50 dark:bg-gray-950/50 border-gray-200 dark:border-gray-800' }} flex justify-between items-center transition-all hover:shadow-sm gap-3">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-sm {{ $item->is_available ? 'text-gray-800 dark:text-gray-200' : 'text-gray-400 dark:text-gray-600 line-through' }} truncate">{{ $item->name }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-0.5 font-semibold">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            @if(!$item->is_available)
                                                <span class="text-[9px] font-black uppercase tracking-wider text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-950/20 px-1.5 py-0.5 rounded mt-1 inline-block">Habis</span>
                                            @endif
                                        </div>
                                        
                                        {{-- Toggle Switch --}}
                                        <button wire:click="toggleAvailability({{ $item->id }})" 
                                                wire:loading.attr="disabled"
                                                wire:target="toggleAvailability({{ $item->id }})"
                                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $item->is_available ? 'bg-[#bc000a]' : 'bg-gray-300 dark:bg-gray-700' }}"
                                                role="switch" aria-checked="{{ $item->is_available ? 'true' : 'false' }}">
                                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out {{ $item->is_available ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-gray-900 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-800 p-12 text-center">
                            <span class="material-symbols-outlined text-4xl text-gray-300 dark:text-gray-600 mb-3">search_off</span>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Tidak ada menu yang sesuai pencarian.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
        
    </main>

    {{-- ── Bottom Navigation Tab Bar ────────────────────────────── --}}
    <nav class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 px-2 py-2 shadow-lg flex justify-around items-center z-20 shrink-0">
        
        {{-- Tab Antrian --}}
        <button wire:click="switchTab('antrian')" class="relative flex flex-col items-center gap-1 px-5 py-2 rounded-2xl transition-all cursor-pointer {{ $activeTab === 'antrian' ? 'bg-[#bc000a] text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            @if($antrianCount > 0)
                <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-yellow-500 text-white text-[10px] font-black flex items-center justify-center shadow-sm">{{ $antrianCount > 9 ? '9+' : $antrianCount }}</span>
            @endif
            <span class="material-symbols-outlined text-[22px] {{ $activeTab === 'antrian' ? 'icon-fill' : '' }}">list_alt</span>
            <span class="text-[10px] font-bold uppercase tracking-wider">Antrian</span>
        </button>

        {{-- Tab Proses --}}
        <button wire:click="switchTab('proses')" class="relative flex flex-col items-center gap-1 px-5 py-2 rounded-2xl transition-all cursor-pointer {{ $activeTab === 'proses' ? 'bg-[#bc000a] text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            @if($masakCount > 0)
                <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-blue-500 text-white text-[10px] font-black flex items-center justify-center shadow-sm">{{ $masakCount > 9 ? '9+' : $masakCount }}</span>
            @endif
            <span class="material-symbols-outlined text-[22px] {{ $activeTab === 'proses' ? 'icon-fill' : '' }}">outdoor_grill</span>
            <span class="text-[10px] font-bold uppercase tracking-wider">Proses</span>
        </button>

        {{-- Tab Riwayat --}}
        <button wire:click="switchTab('riwayat')" class="relative flex flex-col items-center gap-1 px-5 py-2 rounded-2xl transition-all cursor-pointer {{ $activeTab === 'riwayat' ? 'bg-[#bc000a] text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            <span class="material-symbols-outlined text-[22px] {{ $activeTab === 'riwayat' ? 'icon-fill' : '' }}">history</span>
            <span class="text-[10px] font-bold uppercase tracking-wider">Riwayat</span>
        </button>

        {{-- Tab Menu --}}
        <button wire:click="switchTab('menu')" class="relative flex flex-col items-center gap-1 px-5 py-2 rounded-2xl transition-all cursor-pointer {{ $activeTab === 'menu' ? 'bg-[#bc000a] text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            <span class="material-symbols-outlined text-[22px] {{ $activeTab === 'menu' ? 'icon-fill' : '' }}">restaurant_menu</span>
            <span class="text-[10px] font-bold uppercase tracking-wider">Menu</span>
        </button>

    </nav>

    @push('scripts')
    <script>
        // ── Live Clock (persists across Livewire polls) ──────────────
        let kdsClockInterval = null;

        function updateKdsClock() {
            const now = new Date();
            const timeEl = document.getElementById('kds-time');
            const dateEl = document.getElementById('kds-date');
            if (timeEl) {
                const hh = String(now.getHours()).padStart(2, '0');
                const mm = String(now.getMinutes()).padStart(2, '0');
                const ss = String(now.getSeconds()).padStart(2, '0');
                timeEl.textContent = `${hh}.${mm}.${ss}`;
            }
            if (dateEl) {
                dateEl.textContent = now.toLocaleDateString('id-ID', {
                    weekday: 'long', day: '2-digit', month: 'long', year: 'numeric'
                });
            }
        }

        function startKdsClock() {
            updateKdsClock();
            if (!kdsClockInterval) {
                kdsClockInterval = setInterval(updateKdsClock, 1000);
            }
        }

        // Boot clock immediately
        startKdsClock();

        // Re-attach after Livewire morphs the DOM (poll reinit)
        document.addEventListener('livewire:update', function () {
            if (!document.getElementById('kds-time')) return;
            updateKdsClock(); // snap current time immediately after poll
        });
    </script>
    @endpush
</div>
