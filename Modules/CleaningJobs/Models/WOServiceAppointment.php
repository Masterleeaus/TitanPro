<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOServiceAppointment extends Model
{
    use HasFactory;

    protected $table = 'wo_service_appointments';

    protected $fillable = [
        'wo_id', 'work_order_id', 'technician_id', 'start_date', 'start_time',
        'end_date', 'end_time', 'starts_at', 'ends_at', 'location', 'status',
        'notes', 'parent_id',
    ];

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
