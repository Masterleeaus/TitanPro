<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobActivityLog extends Model
{
    use HasFactory;

    protected $table = 'cleaning_job_activity_logs';
    protected $fillable = ['subject_type','subject_id','user_id','event','properties'];
    protected $casts = ['properties' => 'array'];

    public function subject() { return $this->morphTo(); }
}
