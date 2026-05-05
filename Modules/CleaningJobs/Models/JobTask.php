<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cleaning_job_tasks';
    protected $fillable = ['work_order_id','stage_id','milestone_id','title','description','priority','status','start_date','due_date','assigned_to','order','estimated_hours','actual_hours','is_billable','company_id'];
    protected $casts = ['start_date' => 'datetime', 'due_date' => 'datetime', 'estimated_hours' => 'decimal:2', 'actual_hours' => 'decimal:2', 'is_billable' => 'boolean'];

    public function workOrder() { return $this->belongsTo(WorkOrder::class); }
    public function stage() { return $this->belongsTo(JobStage::class, 'stage_id'); }
    public function milestone() { return $this->belongsTo(JobMilestone::class, 'milestone_id'); }
    public function subtasks() { return $this->hasMany(JobSubtask::class, 'task_id')->orderBy('order'); }
    public function comments() { return $this->morphMany(JobComment::class, 'commentable')->latest(); }
    public function files() { return $this->morphMany(JobFile::class, 'fileable')->latest(); }
    public function timesheets() { return $this->hasMany(JobTimesheet::class, 'task_id'); }

    public function completedSubtaskCount(): int { return $this->subtasks->where('is_complete', true)->count(); }
    public function totalSubtaskCount(): int { return $this->subtasks->count(); }
    public function completionPercentage(): int
    {
        $total = max($this->totalSubtaskCount(), 1);
        return min(100, max(0, (int) ceil(($this->completedSubtaskCount() / $total) * 100)));
    }
}
