<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Live Map</x-slot>
        <x-slot name="description">Latest technician locations from the TitanGo mobile app.</x-slot>

        <div class="grid gap-4 lg:grid-cols-[1.5fr_1fr]">
            <div class="flex min-h-[360px] items-center justify-center rounded-2xl border border-dashed border-gray-300 bg-gray-50 text-center dark:border-gray-700 dark:bg-gray-900">
                <div>
                    <div class="text-lg font-semibold text-gray-950 dark:text-white">Field map</div>
                    <div class="mt-1 text-sm text-gray-500">Location records are listed beside the map for mobile dispatch visibility.</div>
                </div>
            </div>
            <div class="space-y-3">
                @forelse ($locations as $location)
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                        <div class="font-semibold text-gray-950 dark:text-white">{{ $location['name'] }}</div>
                        <div class="mt-1 text-xs text-gray-500">{{ $location['latitude'] }}, {{ $location['longitude'] }}</div>
                        <div class="mt-2 text-xs text-gray-500">Updated {{ $location['recorded_at']?->diffForHumans() ?? 'never' }}</div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-300 p-6 text-sm text-gray-500 dark:border-gray-700">No live locations yet.</div>
                @endforelse
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
