<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobStage extends Model
{
    use HasFactory;

    protected $table = 'cleaning_job_stages';

    protected $fillable = ['name','slug','type','order','color','is_default','is_completed','is_active','company_id'];

    protected $casts = ['is_default' => 'boolean', 'is_completed' => 'boolean', 'is_active' => 'boolean'];

    public function tasks() { return $this->hasMany(JobTask::class, 'stage_id'); }
}
