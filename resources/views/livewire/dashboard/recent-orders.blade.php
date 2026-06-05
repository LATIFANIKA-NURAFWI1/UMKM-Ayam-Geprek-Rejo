@if($orders->isEmpty())
    <div class="flex flex-col items-center justify-center py-8 text-zinc-400">
        <flux:icon name="shopping-cart" class="mb-2 h-10 w-10 opacity-30" />
        <p class="text-sm">Belum ada pesanan hari ini</p>
    </div>
@else
    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
        @foreach($orders as $order)
            <div class="flex items-center justify-between py-3">
                <div>
                    <p class="text-sm font-medium text-zinc-900 dark:text-white">
                        #{{ $order->order_number }}
                        @if($order->table_number)
                            <span class="ml-1 text-xs text-zinc-400">· Meja {{ $order->table_number }}</span>
                        @endif
                    </p>
                    <p class="text-xs text-zinc-500">{{ $order->created_at->format('H:i') }} · {{ $order->details->count() }} item</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-zinc-900 dark:text-white">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                    @php
                        $badge = match($order->status) {
                            'pending'    => ['label' => 'Pending',    'class' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400'],
                            'confirmed'  => ['label' => 'Dikonfirmasi','class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400'],
                            'preparing'  => ['label' => 'Dimasak',    'class' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-400'],
                            'completed'  => ['label' => 'Selesai',    'class' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400'],
                            'cancelled'  => ['label' => 'Batal',      'class' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400'],
                            default      => ['label' => $order->status,'class' => 'bg-zinc-100 text-zinc-600'],
                        };
                    @endphp
                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $badge['class'] }}">
                        {{ $badge['label'] }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
@endif
