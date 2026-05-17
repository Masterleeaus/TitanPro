<?php

declare(strict_types=1);

namespace Modules\Budgeting\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiSnapshot extends BaseModel
{
    use HasCompany;
    use SoftDeletes;

    protected $table = 'budget_kpi_snapshots';

    protected $fillable = [
        'company_id',
        'snapshot_date',
        'data',
        'period',
    ];

    protected function casts(): array
    {
        return [
            'snapshot_date' => 'date',
            'data' => 'array',
        ];
    }
}
