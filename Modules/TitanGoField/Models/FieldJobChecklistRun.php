<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FieldJobChecklistRun extends Model
{
    protected $table = 'field_job_checklist_runs';

    protected $fillable = [
        'company_id', 'field_job_id', 'checklist_template_id',
        'completed_by', 'completed_at',
    ];

    protected $casts = ['completed_at' => 'datetime'];

    public function scopeTenant(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    public function fieldJob()
    {
        return $this->belongsTo(FieldJob::class, 'field_job_id');
    }

    public function template()
    {
        return $this->belongsTo(FsmChecklistTemplate::class, 'checklist_template_id');
    }

    public function responses()
    {
        return $this->hasMany(FieldJobChecklistResponse::class, 'checklist_run_id');
    }
}
