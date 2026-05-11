<?php

declare(strict_types=1);

namespace App\Extensions\TitanLeads\System\Models\Leads;

use Illuminate\Database\Eloquent\Model;

class PcStage extends Model
{
    protected $table = 'ext_pc_stages';

    protected $fillable = [
        'user_id',
        'company_id',
        'pipeline_id',
        'name',
        'color',
        'sort_order',
        'is_won',
        'is_lost',
    ];
}
