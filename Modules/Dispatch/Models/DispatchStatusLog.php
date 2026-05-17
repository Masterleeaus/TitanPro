<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class DispatchStatusLog extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'dispatch_status_logs';

    protected $fillable = [
        'company_id',
        'assign_shift_id',
        'work_order_id',
        'appointment_id',
        'from_status',
        'to_status',
        'changed_by',
        'changed_at',
        'notes',
        'metadata'
    ];

    public function assignment()
    {
        return $this->belongsTo(AssignShift::class, 'assign_shift_id');
    }

    public function workOrder()
    {
        return $this->belongsTo(DispatchWorkOrder::class, 'work_order_id');
    }

    public function appointment()
    {
        return $this->belongsTo(DispatchAppointment::class, 'appointment_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(config('dispatch.models.user', \App\Models\User::class), 'changed_by');
    }

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'changed_at' => 'datetime',
        ];
    }
}
