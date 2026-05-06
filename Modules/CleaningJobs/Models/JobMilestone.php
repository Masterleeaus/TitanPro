<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobMilestone extends Model
{
    use HasFactory;

    protected $table = 'cleaning_job_milestones';
    protected $fillable = ['work_order_id','title','summary','status','budget_amount','progress','start_date','end_date','order','company_id'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'budget_amount' => 'decimal:2', 'progress' => 'integer'];

    public function workOrder() { return $this->belongsTo(WorkOrder::class); }
    public function tasks() { return $this->hasMany(JobTask::class, 'milestone_id'); }
}
