<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FieldJobServicePart extends Model
{
    protected $table = 'field_job_service_parts';

    protected $fillable = [
        'company_id', 'field_job_id', 'service_part_id',
        'type', 'item_name', 'qty', 'unit_price', 'amount',
    ];

    protected $casts = [
        'qty'        => 'decimal:4',
        'unit_price' => 'decimal:2',
        'amount'     => 'decimal:2',
    ];

    public function scopeTenant(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    public function fieldJob()
    {
        return $this->belongsTo(FieldJob::class, 'field_job_id');
    }

    public function catalogPart()
    {
        return $this->belongsTo(FsmServicePart::class, 'service_part_id');
    }
}
