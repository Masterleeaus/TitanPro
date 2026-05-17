<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Routing;

use Illuminate\Support\Collection;
use Modules\Dispatch\Models\DispatchRoute;
use Modules\Dispatch\Models\DispatchRouteStop;

class RouteOptimisationService
{
    public function __construct(private readonly TravelTimeService $travelTimeService) {}

    public function sequence(Collection $stops): Collection
    {
        return $stops->sortBy(fn ($stop) => $stop['planned_arrival_at'] ?? $stop->planned_arrival_at ?? null)
            ->values()
            ->map(function ($stop, int $index) {
                if (is_array($stop)) {
                    $stop['sequence'] = $index + 1;
                    return $stop;
                }

                $stop->sequence = $index + 1;
                return $stop;
            });
    }

    public function resequenceRoute(DispatchRoute $route): DispatchRoute
    {
        $previousLocation = null;
        $totalDistance = 0;
        $totalDuration = 0;

        $this->sequence($route->stops()->with('customerLocation')->get())->each(function (DispatchRouteStop $stop) use (&$previousLocation, &$totalDistance, &$totalDuration): void {
            $estimate = $previousLocation && $stop->customerLocation
                ? $this->travelTimeService->estimate($previousLocation, $stop->customerLocation)
                : ['distance_meters' => null, 'duration_seconds' => null];

            $stop->forceFill([
                'travel_seconds_from_previous' => $estimate['duration_seconds'],
                'distance_meters_from_previous' => $estimate['distance_meters'],
            ])->save();

            $totalDistance += (int) ($estimate['distance_meters'] ?? 0);
            $totalDuration += (int) ($estimate['duration_seconds'] ?? 0);
            $previousLocation = $stop->customerLocation;
        });

        $route->forceFill(['total_distance_meters' => $totalDistance, 'total_duration_seconds' => $totalDuration])->save();

        return $route->refresh();
    }
}
