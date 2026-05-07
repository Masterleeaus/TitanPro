<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Sync Health Area</x-slot>
        <x-slot name="description">Last PWA sync state by technician.</x-slot>

        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($rows as $row)
                <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                    <div class="flex items-center justify-between gap-3">
                        <div class="font-semibold text-gray-950 dark:text-white">{{ $row['name'] }}</div>
                        <span @class([
                            'rounded-full px-2 py-0.5 text-xs font-medium',
                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' => ! $row['is_offline'],
                            'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' => $row['is_offline'],
                        ])>{{ $row['is_offline'] ? 'Offline' : 'Healthy' }}</span>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">
                        Last sync: {{ $row['last_sync']?->diffForHumans() ?? 'Never' }}
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 p-6 text-sm text-gray-500 dark:border-gray-700">No technician sync records found.</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
