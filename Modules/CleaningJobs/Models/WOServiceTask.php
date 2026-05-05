<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOServiceTask extends Model
{
    use HasFactory;

    protected $table = 'wo_service_tasks';

    protected $fillable = [
        'wo_id', 'work_order_id', 'service_part_id', 'service_task_id', 'service_task',
        'duration', 'description', 'status', 'qty', 'rate', 'total',
    ];

    public static $status = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'on_hold' => 'On Hold',
        'completed' => 'Completed',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    public function services()
    {
        return $this->hasOne(ServicePart::class, 'id', 'service_part_id');
    }

    public function catalogTask()
    {
        return $this->belongsTo(ServiceTask::class, 'service_task_id');
    }
}
