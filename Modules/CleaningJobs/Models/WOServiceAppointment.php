<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\CleaningJobs\Traits\BelongsToTenant;

class WOServiceAppointment extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'wo_service_appointments';

    protected $fillable = [
        'wo_id',
        'work_order_id',
        'technician_id',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'starts_at',
        'ends_at',
        'location',
        'status',
        'notes',
        'parent_id',
        'company_id',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'company_id' => 'integer',
            'parent_id' => 'integer',
        ];
    }

    public static $status = [
        'pending' => 'Pending',
        'schedule' => 'Schedule',
        'scheduled' => 'Scheduled',
        'dispatched' => 'Dispatched',
        'in_progress' => 'In Progress',
        'on_hold' => 'On Hold',
        'completed' => 'Completed',
        'done' => 'Done',
        'cancelled' => 'Cancelled',
        'canceled' => 'Canceled',
        'reschedule' => 'Reschedule',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }
}
