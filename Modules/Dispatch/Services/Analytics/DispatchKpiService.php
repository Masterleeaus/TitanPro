<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Analytics;

use Carbon\CarbonImmutable;
use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Models\DispatchWorkOrder;

class DispatchKpiService
{
    public function summary(?string $from = null, ?string $to = null): array
    {
        $start = $from ? CarbonImmutable::parse($from)->startOfDay() : now()->startOfMonth();
        $end = $to ? CarbonImmutable::parse($to)->endOfDay() : now()->endOfMonth();

        $workOrders = DispatchWorkOrder::query()->whereBetween('created_at', [$start, $end]);
        $appointments = DispatchAppointment::query()->whereBetween('starts_at', [$start, $end]);

        return [
            'window' => ['from' => $start->toDateString(), 'to' => $end->toDateString()],
            'work_orders_total' => (clone $workOrders)->count(),
            'work_orders_completed' => (clone $workOrders)->where('status', 'completed')->count(),
            'work_orders_in_progress' => (clone $workOrders)->where('status', 'in_progress')->count(),
            'appointments_total' => (clone $appointments)->count(),
            'appointments_scheduled' => (clone $appointments)->where('status', 'scheduled')->count(),
            'appointments_cancelled' => (clone $appointments)->where('status', 'cancelled')->count(),
        ];
    }
}
