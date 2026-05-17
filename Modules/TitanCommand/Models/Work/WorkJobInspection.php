<?php

namespace Modules\TitanCommand\Models\Work;

use Illuminate\Database\Eloquent\Model;

class WorkJobInspection extends Model
{
    protected $table = 'work_jobs_inspections';

    protected $guarded = [];

    public function scopeTenant($q, int $companyId, int $userId)
    {
        return $q->where('company_id', $companyId)->where('user_id', $userId);
    }
}
