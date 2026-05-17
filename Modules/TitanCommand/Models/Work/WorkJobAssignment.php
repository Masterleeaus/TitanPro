<?php

declare(strict_types=1);

namespace Modules\TitanCommand\Models\Work;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WorkJobAssignment extends Model
{
    protected $table = 'work_jobs_assignments';

    protected $guarded = ['id'];

    protected $casts = [
        'meta_json' => 'array',
    ];

    public function scopeTenant(Builder $q, int $companyId, int $userId): Builder
    {
        return $q->where('company_id', $companyId)->where('user_id', $userId);
    }
}
