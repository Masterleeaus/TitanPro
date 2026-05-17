<?php

declare(strict_types=1);

namespace Modules\Budgeting\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntegrationRun extends BaseModel
{
    use HasCompany;
    use SoftDeletes;

    protected $table = 'budget_integration_runs';

    protected $fillable = [
        'company_id',
        'source',
        'status',
        'records_synced',
        'error_message',
        'ran_at',
    ];

    protected function casts(): array
    {
        return [
            'records_synced' => 'integer',
            'ran_at' => 'datetime',
        ];
    }
}
