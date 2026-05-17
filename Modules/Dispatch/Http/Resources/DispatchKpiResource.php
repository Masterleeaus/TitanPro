<?php

declare(strict_types=1);

namespace Modules\Dispatch\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DispatchKpiResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'window' => $this->resource['window'] ?? [],
            'work_orders' => [
                'total' => $this->resource['work_orders_total'] ?? 0,
                'completed' => $this->resource['work_orders_completed'] ?? 0,
                'in_progress' => $this->resource['work_orders_in_progress'] ?? 0,
            ],
            'appointments' => [
                'total' => $this->resource['appointments_total'] ?? 0,
                'scheduled' => $this->resource['appointments_scheduled'] ?? 0,
                'cancelled' => $this->resource['appointments_cancelled'] ?? 0,
            ],
        ];
    }
}
