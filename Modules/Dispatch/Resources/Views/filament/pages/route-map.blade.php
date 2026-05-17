<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Route map readiness</x-slot>
        <p class="text-sm text-gray-600 dark:text-gray-400">This page is ready for a map provider. Current route plans and stop sequences are listed below for dispatch validation.</p>
    </x-filament::section>

    <div class="space-y-4">
        @forelse ($routes as $route)
            <x-filament::section>
                <x-slot name="heading">{{ $route->name ?? 'Route #'.$route->id }} · {{ optional($route->route_date)->format('d M Y') }}</x-slot>
                <div class="mb-3 text-sm text-gray-500">{{ $route->technician?->name ?? 'No technician' }} · {{ ucfirst((string) $route->status) }} · {{ $route->total_duration_seconds ? round($route->total_duration_seconds / 60).' min' : 'travel pending' }}</div>
                <ol class="space-y-2">
                    @forelse ($route->stops as $stop)
                        <li class="rounded-lg border p-3 dark:border-gray-800">
                            <span class="font-medium">#{{ $stop->sequence }}</span>
                            {{ $stop->workOrder?->title ?? $stop->workOrder?->wo_detail ?? 'Stop #'.$stop->id }}
                            <div class="text-xs text-gray-500">{{ $stop->customerLocation?->full_address ?? 'No mapped location' }}</div>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500">No stops planned for this route.</li>
                    @endforelse
                </ol>
            </x-filament::section>
        @empty
            <x-filament::section>
                <p class="text-sm text-gray-500">No dispatch routes created yet.</p>
            </x-filament::section>
        @endforelse
    </div>
</x-filament-panels::page>
