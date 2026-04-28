@php
    use App\Support\CleaningAdminMetrics;
    $locations = CleaningAdminMetrics::latestCleanerLocations();
    $points = $locations->map(fn ($location) => [
        'name' => $location->user?->name ?? 'Cleaner #' . $location->user_id,
        'lat' => (float) $location->latitude,
        'lng' => (float) $location->longitude,
        'recorded' => optional($location->recorded_at)->diffForHumans(),
        'is_stale' => $location->recorded_at ? $location->recorded_at->lt(CleaningAdminMetrics::staleCleanerThreshold()) : true,
        'maps_url' => 'https://www.google.com/maps/search/?api=1&query=' . urlencode($location->latitude . ',' . $location->longitude),
    ])->values();
    $firstPoint = $points->first();
    $mapId = 'cleaner-live-map-' . uniqid();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Live Cleaner Tracking</x-slot>
        <x-slot name="description">Latest technician PWA location pings linked into the admin dashboard.</x-slot>

        @if ($points->isNotEmpty())
            <div class="grid gap-4 lg:grid-cols-[2fr_1fr]">
                <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                    <div id="{{ $mapId }}" style="height: 360px; width: 100%;"></div>
                </div>
                <div class="space-y-3">
                    @foreach ($points as $point)
                        <a href="{{ $point['maps_url'] }}" target="_blank" class="block rounded-xl border border-gray-200 p-3 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                            <div class="flex items-center justify-between gap-3">
                                <div class="font-semibold text-gray-950 dark:text-white">{{ $point['name'] }}</div>
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-xs font-medium',
                                    'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' => ! $point['is_stale'],
                                    'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' => $point['is_stale'],
                                ])>{{ $point['is_stale'] ? 'stale' : 'live' }}</span>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">{{ $point['recorded'] ?? 'No timestamp' }}</div>
                            <div class="mt-1 text-xs text-gray-500">{{ number_format($point['lat'], 5) }}, {{ number_format($point['lng'], 5) }}</div>
                        </a>
                    @endforeach
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
                        const map = L.map(mapId).setView([first.lat, first.lng], points.length > 1 ? 11 : 13);
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
        @else
            <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center dark:border-gray-700">
                <div class="text-lg font-semibold text-gray-950 dark:text-white">No cleaner location pings yet</div>
                <p class="mt-2 text-sm text-gray-500">Open the cleaner PWA, enable location sharing, and the latest cleaner positions will appear here.</p>
                <a href="/technician/dashboard" target="_blank" class="mt-4 inline-flex rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-500">Open Cleaner PWA</a>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
