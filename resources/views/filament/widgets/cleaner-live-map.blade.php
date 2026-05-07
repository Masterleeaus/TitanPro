@php
    use App\Support\CleaningAdminMetrics;
    $technicians = CleaningAdminMetrics::cleanerMapRoster();
    $points = $technicians
        ->filter(fn (array $technician) => $technician['location'] !== null)
        ->map(fn (array $technician) => [
        'name' => $technician['name'],
        'lat' => (float) $technician['location']->latitude,
        'lng' => (float) $technician['location']->longitude,
        'recorded' => optional($technician['location']->recorded_at)->diffForHumans(),
        'is_stale' => $technician['location']->recorded_at
            ? $technician['location']->recorded_at->lt(CleaningAdminMetrics::staleCleanerThreshold())
            : true,
        'maps_url' => 'https://www.google.com/maps/search/?api=1&query=' . urlencode($technician['location']->latitude . ',' . $technician['location']->longitude),
    ])->values();
    $mapId = 'cleaner-live-map-' . uniqid();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Live Cleaner Tracking</x-slot>
        <x-slot name="description">Latest technician PWA location pings linked into the admin dashboard.</x-slot>

        <div class="mb-4 flex flex-wrap gap-2">
            <x-filament::button wire:click="$refresh" icon="heroicon-o-arrow-path">
                Refresh Locations
            </x-filament::button>
            <x-filament::button tag="a" href="{{ url('/titango') }}" target="_blank" color="gray" icon="heroicon-o-device-phone-mobile">
                Open TitanGo
            </x-filament::button>
        </div>

        <div class="grid gap-4 lg:grid-cols-[2fr_1fr]">
            <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                <div id="{{ $mapId }}" style="height: 360px; width: 100%;"></div>
            </div>
            <div class="space-y-3">
                @forelse ($technicians as $technician)
                    @if ($technician['location'])
                        @php
                            $isStale = $technician['location']->recorded_at
                                ? $technician['location']->recorded_at->lt(CleaningAdminMetrics::staleCleanerThreshold())
                                : true;
                            $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($technician['location']->latitude . ',' . $technician['location']->longitude);
                        @endphp
                        <a href="{{ $mapsUrl }}" target="_blank" class="block rounded-xl border border-gray-200 p-3 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                            <div class="flex items-center justify-between gap-3">
                                <div class="font-semibold text-gray-950 dark:text-white">{{ $technician['name'] }}</div>
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-xs font-medium',
                                    'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' => ! $isStale,
                                    'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' => $isStale,
                                ])>{{ $isStale ? 'stale' : 'live' }}</span>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">{{ optional($technician['location']->recorded_at)->diffForHumans() ?? 'No timestamp' }}</div>
                            <div class="mt-1 text-xs text-gray-500">{{ number_format((float) $technician['location']->latitude, 5) }}, {{ number_format((float) $technician['location']->longitude, 5) }}</div>
                        </a>
                    @else
                        <div class="rounded-xl border border-dashed border-gray-300 p-3 dark:border-gray-700">
                            <div class="flex items-center justify-between gap-3">
                                <div class="font-semibold text-gray-950 dark:text-white">{{ $technician['name'] }}</div>
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 dark:bg-slate-700 dark:text-slate-200">unknown</span>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">Location unknown</div>
                        </div>
                    @endif
                @empty
                    <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center dark:border-gray-700">
                        <div class="text-sm font-semibold text-gray-950 dark:text-white">No technicians configured yet</div>
                        <p class="mt-1 text-xs text-gray-500">Assign users the technician role to populate this panel.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIINfQTXAX4I5b+2NQp2I4GZ9cc5MJyFIgE=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            (function () {
                const points = @json($points);
                const mapId = @json($mapId);
                const init = function () {
                    if (!window.L || !document.getElementById(mapId)) return;
                    const first = points[0] ?? { lat: -33.8688, lng: 151.2093 };
                    const zoom = points.length > 1 ? 11 : (points.length === 1 ? 13 : 4);
                    const map = L.map(mapId).setView([first.lat, first.lng], zoom);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);
                    const bounds = [];
                    points.forEach(function (point) {
                        const marker = L.marker([point.lat, point.lng]).addTo(map);
                        marker.bindPopup('<strong>' + point.name + '</strong><br>' + (point.recorded ?? 'No timestamp'));
                        bounds.push([point.lat, point.lng]);
                    });
                    if (bounds.length > 1) {
                        map.fitBounds(bounds, { padding: [30, 30] });
                    }
                    setTimeout(function () { map.invalidateSize(); }, 300);
                };
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', init);
                } else {
                    init();
                }
            })();
        </script>
    </x-filament::section>
</x-filament-widgets::widget>
