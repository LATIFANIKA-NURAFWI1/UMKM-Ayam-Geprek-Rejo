{{-- Poll every 5 seconds — calls checkStatus() to detect cashier confirmation --}}
<div wire:poll.5s="checkStatus" class="min-h-screen bg-gray-50 flex flex-col">

    @if($order->payment_method === 'qris')

        {{-- ===================================================================
             QRIS PAYMENT STATE
             =================================================================== --}}

        {{-- Header --}}
        <div class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-lg mx-auto px-4 py-4 text-center">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12v.01M12 3.01V3M4 20h4m-4 0V4m0 0h4m12 0h-4m4 0v4m0 12v-4M4 8h4V4M4 4h4m12 0h-4m4 0v4m-4 16v-4m0 4h-4"/>
                    </svg>
                </div>
                <h1 class="text-lg font-bold text-gray-900">Scan QRIS Untuk Membayar</h1>
                <p class="text-sm text-gray-500 mt-0.5">Bayar melalui aplikasi dompet digital Anda</p>
            </div>
        </div>

        {{-- Main content --}}
        <div class="flex-1 max-w-lg mx-auto w-full px-4 py-6 space-y-5">

            {{-- Total Amount --}}
            <div class="bg-white rounded-2xl shadow-sm px-6 py-5 text-center">
                <p class="text-sm text-gray-500 mb-1">Total Pembayaran</p>
                <p class="text-4xl font-black text-gray-900 tracking-tight">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Order #{{ $order->order_number }}</p>
            </div>

            {{-- QRIS Image --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                @if($this->qrisImageUrl)
                    <div class="p-4 flex flex-col items-center">
                        <img src="{{ $this->qrisImageUrl }}"
                             alt="QRIS Geprek Rejo"
                             id="qris-image"
                             class="w-64 h-64 object-contain rounded-xl">
                        <p class="text-xs text-gray-400 mt-3">Arahkan kamera ke QR di atas</p>

                        {{-- Download / Screenshot buttons --}}
                        <div class="flex gap-2 mt-3 w-full">
                            <a href="{{ $this->qrisImageUrl }}"
                               download="QRIS-Geprek-Rejo.png"
                               class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-orange-500 text-white text-sm font-semibold rounded-xl hover:bg-orange-600 transition active:scale-95">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download QR
                            </a>
                            <button type="button"
                                onclick="
                                    if(navigator.share){
                                        navigator.share({title:'QRIS Geprek Rejo', url:'{{ $this->qrisImageUrl }}'})
                                    } else {
                                        alert('Screenshot layar ini untuk menyimpan QR Code')
                                    }
                                "
                                class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition active:scale-95">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                                Bagikan
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">💡 Tip: Screenshot halaman ini untuk menyimpan QR</p>
                    </div>
                @else
                    {{-- Fallback when no QR image is configured --}}
                    <div class="flex flex-col items-center justify-center py-10 px-6 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                            <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12v.01M12 3.01V3M4 20h4m-4 0V4m0 0h4m12 0h-4m4 0v4m0 12v-4M4 8h4V4M4 4h4m12 0h-4m4 0v4m-4 16v-4m0 4h-4"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-700">Minta kasir tunjukkan QR QRIS</p>
                        <p class="text-sm text-gray-400 mt-1">Atau scan QR yang tersedia di meja kasir</p>
                    </div>
                @endif
            </div>

            {{-- Payment Steps --}}
            <div class="bg-white rounded-2xl shadow-sm px-4 py-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Cara Pembayaran</p>
                <div class="space-y-2.5">
                    @foreach([
                        ['1', 'Buka aplikasi dompet digital (GoPay, OVO, Dana, dll.)'],
                        ['2', 'Pilih fitur Scan QR atau QRIS'],
                        ['3', 'Arahkan kamera ke QR Code di atas'],
                        ['4', 'Masukkan nominal Rp '.number_format($order->total_amount, 0, ',', '.')],
                        ['5', 'Konfirmasi & selesaikan pembayaran'],
                    ] as [$step, $desc])
                        <div class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-orange-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                                {{ $step }}
                            </span>
                            <p class="text-sm text-gray-600 leading-tight pt-0.5">{{ $desc }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Waiting indicator --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3.5 flex items-center gap-3">
                <span class="relative flex-shrink-0">
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </span>
                <div>
                    <p class="text-sm font-semibold text-amber-800">Menunggu konfirmasi kasir...</p>
                    <p class="text-xs text-amber-600 mt-0.5">Tunjukkan bukti bayar kepada kasir</p>
                </div>
            </div>

        </div>

    @else

        {{-- ===================================================================
             CASH PAYMENT STATE
             =================================================================== --}}

        {{-- Header --}}
        <div class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-lg mx-auto px-4 py-4 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h1 class="text-lg font-bold text-gray-900">Bayar Tunai ke Kasir</h1>
                <p class="text-sm text-gray-500 mt-0.5">Serahkan uang tunai kepada kasir kami</p>
            </div>
        </div>

        {{-- Main content --}}
        <div class="flex-1 max-w-lg mx-auto w-full px-4 py-6 space-y-5">

            {{-- Big total amount --}}
            <div class="bg-white rounded-2xl shadow-sm px-6 py-8 text-center">
                <p class="text-sm text-gray-500 mb-2 font-medium">💵 Silakan bayar sebesar</p>
                <p class="text-5xl font-black text-orange-500 tracking-tight leading-none">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-400 mt-3">Order #{{ $order->order_number }}</p>
            </div>

            {{-- Cash payment illustration --}}
            <div class="bg-white rounded-2xl shadow-sm px-6 py-8 flex flex-col items-center gap-4">
                <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="text-center">
                    <p class="font-semibold text-gray-900">Serahkan uang ke kasir</p>
                    <p class="text-sm text-gray-500 mt-1 leading-relaxed">
                        Kasir akan mengkonfirmasi pembayaran Anda dan memberikan kembalian jika ada.
                    </p>
                </div>
            </div>

            {{-- Waiting indicator --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3.5 flex items-center gap-3">
                <span class="relative flex-shrink-0">
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </span>
                <div>
                    <p class="text-sm font-semibold text-amber-800">Menunggu konfirmasi kasir...</p>
                    <p class="text-xs text-amber-600 mt-0.5">Halaman akan otomatis berlanjut setelah dikonfirmasi</p>
                </div>
            </div>

        </div>

    @endif

    {{-- =====================================================================
         BOTTOM: ORDER INFO BAR
         ===================================================================== --}}
    <div class="sticky bottom-0 bg-white border-t border-gray-200 shadow-[0_-4px_16px_rgba(0,0,0,0.06)]">
        <div class="max-w-lg mx-auto px-4 py-3 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400">Nomor Pesanan</p>
                <p class="text-sm font-bold text-gray-900 tracking-wide">{{ $order->order_number }}</p>
            </div>
            <div class="h-8 w-px bg-gray-200"></div>
            <div class="text-right">
                <p class="text-xs text-gray-400">Pelanggan</p>
                <p class="text-sm font-semibold text-gray-900">{{ $customerName }}</p>
            </div>
            <div class="h-8 w-px bg-gray-200"></div>
            <div class="text-right">
                <p class="text-xs text-gray-400">Metode</p>
                <p class="text-sm font-semibold text-gray-900 uppercase">
                    {{ $order->payment_method === 'qris' ? 'QRIS' : 'Tunai' }}
                </p>
            </div>
        </div>
    </div>

</div>
