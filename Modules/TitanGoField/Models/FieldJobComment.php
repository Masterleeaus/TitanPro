<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FieldJobComment extends Model
{
    protected $table = 'field_job_comments';

    protected $fillable = ['company_id', 'field_job_id', 'user_id', 'body', 'attachment_path'];

    public function scopeTenant(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    public function fieldJob()
    {
        return $this->belongsTo(FieldJob::class, 'field_job_id');
    }
}
