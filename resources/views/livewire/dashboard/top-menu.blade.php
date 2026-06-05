@if($topMenus->isEmpty())
    <div class="flex flex-col items-center justify-center py-8 text-zinc-400">
        <flux:icon name="fire" class="mb-2 h-10 w-10 opacity-30" />
        <p class="text-sm">Belum ada data penjualan hari ini</p>
    </div>
@else
    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
        @foreach($topMenus as $index => $item)
            <div class="flex items-center gap-3 py-3">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-orange-600 dark:bg-orange-900/40 dark:text-orange-400">
                    {{ $index + 1 }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="truncate text-sm font-medium text-zinc-900 dark:text-white">
                        {{ $item->menuItem?->name ?? $item->menu_item_name }}
                    </p>
                    <p class="text-xs text-zinc-500">{{ $item->menuItem?->category?->name ?? '' }}</p>
                </div>
                <span class="shrink-0 text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    {{ $item->total_terjual }}x
                </span>
            </div>
        @endforeach
    </div>
@endif
