<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FieldJobAsset extends Model
{
    protected $table = 'field_job_assets';

    protected $fillable = ['company_id', 'field_job_id', 'asset_id', 'note'];

    public function scopeTenant(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    public function fieldJob()
    {
        return $this->belongsTo(FieldJob::class, 'field_job_id');
    }
}
