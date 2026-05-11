<?php

namespace App\Extensions\TitanCommand\System\Models\Work;

use Illuminate\Database\Eloquent\Model;

class WorkJobInspectionItem extends Model
{
    protected $table = 'work_jobs_inspection_items';

    protected $guarded = [];

    protected $casts = [
        'meta_json' => 'array',
    ];

    public function scopeTenant($q, int $companyId, int $userId)
    {
        return $q->where('company_id', $companyId)->where('user_id', $userId);
    }
}
