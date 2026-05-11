<?php

namespace App\Extensions\TitanCommand\System\Models\Work;

use Illuminate\Database\Eloquent\Model;

class WorkJobTemplate extends Model
{
    protected $table = 'work_jobs_templates';
    protected $guarded = [];
    protected $casts = [
        'meta_json' => 'array',
    ];

    public function scopeTenant($q, int $companyId, int $userId)
    {
        return $q->where('company_id', $companyId)->where('user_id', $userId);
    }
}
