<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FieldJobInspection extends Model
{
    protected $table = 'field_job_inspections';

    protected $fillable = [
        'company_id', 'field_job_id', 'template_name',
        'completed_by', 'completed_at', 'passed', 'pdf_path', 'responses',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'passed'       => 'boolean',
        'responses'    => 'array',
    ];

    public function scopeTenant(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    public function fieldJob()
    {
        return $this->belongsTo(FieldJob::class, 'field_job_id');
    }
}
