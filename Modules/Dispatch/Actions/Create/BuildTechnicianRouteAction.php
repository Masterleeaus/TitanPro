<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Create;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Models\CustomerLocation;
use Modules\Dispatch\Models\DispatchRoute;
use Modules\Dispatch\Models\DispatchRouteStop;
use Modules\Dispatch\Services\Routing\RouteOptimisationService;

class BuildTechnicianRouteAction
{
    public function __construct(private readonly RouteOptimisationService $routeOptimisationService) {}

    public function execute(int $technicianId, string|Carbon $routeDate, array $appointmentIds = [], array $attributes = []): DispatchRoute
    {
        $date = $routeDate instanceof Carbon ? $routeDate : Carbon::parse($routeDate);

        return DB::transaction(function () use ($technicianId, $date, $appointmentIds, $attributes): DispatchRoute {
            $route = DispatchRoute::query()->updateOrCreate(
                [
                    'technician_id' => $technicianId,
                    'route_date' => $date->toDateString(),
                    'name' => $attributes['name'] ?? 'Route '.$date->toDateString(),
                ],
                [
                    'company_id' => $attributes['company_id'] ?? null,
                    'status' => $attributes['status'] ?? 'draft',
                    'metadata' => $attributes['metadata'] ?? [],
                ]
            );

            $appointments = DispatchAppointment::query()
                ->whereIn('id', $appointmentIds)
                ->with('workOrder')
                ->orderBy('starts_at')
                ->get();

            foreach ($appointments as $index => $appointment) {
                $locationId = $attributes['location_map'][$appointment->id] ?? null;

                if (! $locationId && ! empty($appointment->location)) {
                    $locationId = CustomerLocation::query()->firstOrCreate(
                        ['company_id' => $appointment->company_id ?? null, 'name' => $appointment->location],
                        ['address_line_1' => $appointment->location, 'active' => true]
                    )->id;
                }

                DispatchRouteStop::query()->updateOrCreate(
                    ['dispatch_route_id' => $route->id, 'appointment_id' => $appointment->id],
                    [
                        'company_id' => $appointment->company_id ?? $attributes['company_id'] ?? null,
                        'work_order_id' => $appointment->work_order_id,
                        'customer_location_id' => $locationId,
                        'sequence' => $index + 1,
                        'status' => 'planned',
                        'planned_arrival_at' => $appointment->starts_at,
                        'planned_departure_at' => $appointment->ends_at,
                    ]
                );
            }

            return $this->routeOptimisationService->resequenceRoute($route->refresh());
        });
    }
}
