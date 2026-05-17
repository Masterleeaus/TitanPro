<?php

namespace Modules\TitanCommand\Models\Work;

use Illuminate\Database\Eloquent\Model;

class WorkJobReport extends Model
{
    protected $table = 'work_jobs_reports';

    protected $guarded = [];

    protected $casts = [
        'params_json' => 'array',
        'result_json' => 'array',
    ];

    public function scopeTenant($q, int $companyId, int $userId)
    {
        return $q->where('company_id', $companyId)->where('user_id', $userId);
    }
}
