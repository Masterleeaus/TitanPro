<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Routing;

use Modules\Dispatch\Models\CustomerLocation;

class TravelTimeService
{
    public function estimate(array|CustomerLocation $origin, array|CustomerLocation $destination): array
    {
        $origin = $this->normalise($origin);
        $destination = $this->normalise($destination);
        $distance = $this->haversineMeters($origin['latitude'] ?? null, $origin['longitude'] ?? null, $destination['latitude'] ?? null, $destination['longitude'] ?? null);
        $duration = $distance === null ? null : (int) ceil(($distance / 1000) / 35 * 3600); // conservative 35km/h urban average

        return [
            'distance_meters' => $distance,
            'duration_seconds' => $duration,
            'provider' => 'haversine_fallback',
            'origin' => $origin,
            'destination' => $destination,
        ];
    }

    private function normalise(array|CustomerLocation $location): array
    {
        if ($location instanceof CustomerLocation) {
            return ['id' => $location->id, 'latitude' => $location->latitude, 'longitude' => $location->longitude, 'address' => $location->full_address];
        }

        return $location;
    }

    private function haversineMeters(mixed $lat1, mixed $lon1, mixed $lat2, mixed $lon2): ?int
    {
        if ($lat1 === null || $lon1 === null || $lat2 === null || $lon2 === null) {
            return null;
        }

        $earthRadius = 6371000;
        $dLat = deg2rad((float) $lat2 - (float) $lat1);
        $dLon = deg2rad((float) $lon2 - (float) $lon1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad((float) $lat1)) * cos(deg2rad((float) $lat2)) * sin($dLon / 2) ** 2;

        return (int) round($earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a)));
    }
}
