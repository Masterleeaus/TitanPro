<?php

declare(strict_types=1);

namespace Modules\TitanCommand\Models\Work;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WorkJobEvent extends Model
{
    protected $table = 'work_jobs_events';

    protected $guarded = ['id'];

    protected $casts = [
        'payload_json' => 'array',
        'occurred_at'  => 'datetime',
    ];

    public function scopeTenant(Builder $q, int $companyId, int $userId): Builder
    {
        return $q->where('company_id', $companyId)->where('user_id', $userId);
    }
}
