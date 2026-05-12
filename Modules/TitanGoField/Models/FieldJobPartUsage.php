<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FieldJobPartUsage extends Model
{
    protected $table = 'field_job_part_usages';

    protected $fillable = [
        'company_id', 'field_job_id', 'item_id', 'item_name',
        'qty', 'unit_price', 'source_location',
    ];

    protected $casts = [
        'qty'        => 'decimal:4',
        'unit_price' => 'decimal:2',
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
