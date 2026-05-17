<?php

declare(strict_types=1);

namespace App\Extensions\TitanLeads\System\Models\Leads;

use Illuminate\Database\Eloquent\Model;

class PcPipeline extends Model
{
    protected $table = 'ext_pc_pipelines';

    protected $fillable = [
        'user_id',
        'company_id',
        'name',
        'sort_order',
        'is_active',
    ];
}
