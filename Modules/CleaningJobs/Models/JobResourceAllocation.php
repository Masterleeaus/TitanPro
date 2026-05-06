<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobResourceAllocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cleaning_job_resource_allocations';
    protected $fillable = ['work_order_id','task_id','user_id','start_date','end_date','allocation_percentage','hours_per_day','allocation_type','notes','is_billable','is_confirmed','status','created_by_id','company_id'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'allocation_percentage' => 'decimal:2', 'hours_per_day' => 'decimal:2', 'is_billable' => 'boolean', 'is_confirmed' => 'boolean'];

    public function workOrder() { return $this->belongsTo(WorkOrder::class); }
    public function task() { return $this->belongsTo(JobTask::class, 'task_id'); }
    public function user() { return $this->belongsTo(config('cleaningjobs.models.user', \App\Models\User::class)); }
}
