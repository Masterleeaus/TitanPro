<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class DispatchRouteStop extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'dispatch_route_stops';

    protected $fillable = ['company_id', 'dispatch_route_id', 'work_order_id', 'appointment_id', 'customer_location_id', 'sequence', 'status', 'planned_arrival_at', 'planned_departure_at', 'actual_arrival_at', 'actual_departure_at', 'travel_seconds_from_previous', 'distance_meters_from_previous', 'notes'];

    protected function casts(): array
    {
        return ['sequence' => 'integer', 'planned_arrival_at' => 'datetime', 'planned_departure_at' => 'datetime', 'actual_arrival_at' => 'datetime', 'actual_departure_at' => 'datetime'];
    }

    public function route()
    {
        return $this->belongsTo(DispatchRoute::class, 'dispatch_route_id');
    }

    public function workOrder()
    {
        return $this->belongsTo(DispatchWorkOrder::class, 'work_order_id');
    }

    public function appointment()
    {
        return $this->belongsTo(DispatchAppointment::class, 'appointment_id');
    }

    public function customerLocation()
    {
        return $this->belongsTo(CustomerLocation::class, 'customer_location_id');
    }
}
