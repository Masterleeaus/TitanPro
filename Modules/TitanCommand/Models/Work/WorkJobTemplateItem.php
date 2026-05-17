<?php

namespace Modules\TitanCommand\Models\Work;

use Illuminate\Database\Eloquent\Model;

class WorkJobTemplateItem extends Model
{
    protected $table = 'work_jobs_template_items';
    protected $guarded = [];
    protected $casts = [
        'schema_json' => 'array',
    ];

    public function scopeTenant($q, int $companyId, int $userId)
    {
        return $q->where('company_id', $companyId)->where('user_id', $userId);
    }
}
