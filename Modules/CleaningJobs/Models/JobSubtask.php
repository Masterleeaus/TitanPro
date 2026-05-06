<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSubtask extends Model
{
    use HasFactory;

    protected $table = 'cleaning_job_subtasks';
    protected $fillable = ['task_id','title','is_complete','order','completed_by_id','completed_at'];
    protected $casts = ['is_complete' => 'boolean', 'completed_at' => 'datetime'];

    public function task() { return $this->belongsTo(JobTask::class, 'task_id'); }
}
