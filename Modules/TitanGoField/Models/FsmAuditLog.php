<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FsmAuditLog extends Model
{
    protected $table = 'fsm_audit_logs';

    protected $fillable = [
        'company_id', 'entity_type', 'entity_id',
        'user_id', 'action', 'before', 'after', 'ip',
    ];

    protected $casts = [
        'before' => 'array',
        'after'  => 'array',
    ];

    public function scopeTenant(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }
}
