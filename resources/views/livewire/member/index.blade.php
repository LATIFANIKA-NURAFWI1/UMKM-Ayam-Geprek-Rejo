<x-layouts::app :title="__('Member')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <flux:heading size="xl" level="1">Member</flux:heading>
                <flux:text class="mt-1">Daftar member terdaftar dan poin loyalitas</flux:text>
            </div>
        </div>

        <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari nama atau nomor HP…" icon="magnifying-glass" class="w-full sm:w-72" />

        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">No. HP</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Poin</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($members as $member)
                        <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-orange-100 text-sm font-bold text-orange-600 dark:bg-orange-900/40">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-zinc-900 dark:text-white">{{ $member->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-zinc-500">{{ $member->phone }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="font-semibold text-orange-600 dark:text-orange-400">{{ number_format($member->points) }}</span>
                                <span class="text-xs text-zinc-400"> pts</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-zinc-400">{{ $member->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-sm text-zinc-400">Belum ada member terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $members->links() }}</div>

    </div>
</x-layouts::app>
