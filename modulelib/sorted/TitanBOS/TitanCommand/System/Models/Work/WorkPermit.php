<?php

declare(strict_types=1);

namespace App\Extensions\TitanCommand\System\Models\Work;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WorkPermit extends Model
{
    protected $table = 'work_permits';

    protected $guarded = ['id'];

    protected $casts = [
        'meta_json' => 'array',
        'payload_json' => 'array',
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
        'completed_at' => 'datetime',
        'archived_at' => 'datetime',
        'occurred_at' => 'datetime',
        'due_at' => 'datetime',
        'checked_at' => 'datetime',
    ];

    public function scopeTenant(Builder $q, int $companyId, int $userId): Builder
    {
        return $q->where('company_id', $companyId)->where('user_id', $userId);
    }
}
