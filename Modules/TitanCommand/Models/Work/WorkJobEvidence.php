<?php

namespace Modules\TitanCommand\Models\Work;

use Illuminate\Database\Eloquent\Model;

class WorkJobEvidence extends Model
{
    protected $table = 'work_jobs_evidence';

    protected $guarded = [];

    protected $casts = [
        'meta_json' => 'array',
    ];

    public function scopeTenant($q, int $companyId, int $userId)
    {
        return $q->where('company_id', $companyId)->where('user_id', $userId);
    }
}
