<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FsmServicePart extends Model
{
    protected $table = 'fsm_service_parts';

    protected $fillable = ['company_id', 'title', 'sku', 'unit', 'price', 'description'];

    protected $casts = ['price' => 'decimal:2'];

    public function scopeTenant(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }
}
