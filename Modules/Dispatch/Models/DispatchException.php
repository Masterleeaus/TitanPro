<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class DispatchException extends Model
{
    use BelongsToTenant;

    protected $table = 'dispatch_exceptions';

    protected $fillable = ['company_id', 'work_order_id', 'appointment_id', 'technician_id', 'type', 'severity', 'status', 'message', 'resolved_by', 'resolved_at', 'metadata'];

    protected function casts(): array
    {
        return ['resolved_at' => 'datetime', 'metadata' => 'array'];
    }

    public function workOrder()
    {
        return $this->belongsTo(DispatchWorkOrder::class, 'work_order_id');
    }

    public function appointment()
    {
        return $this->belongsTo(DispatchAppointment::class, 'appointment_id');
    }
}
