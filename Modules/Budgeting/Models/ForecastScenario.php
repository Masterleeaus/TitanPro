<?php

declare(strict_types=1);

namespace Modules\Budgeting\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForecastScenario extends BaseModel
{
    use HasCompany;
    use SoftDeletes;

    protected $table = 'budget_forecast_scenarios';

    protected $fillable = [
        'company_id',
        'name',
        'period_start',
        'period_end',
        'model_version',
        'scenario_data',
        'status',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'scenario_data' => 'array',
            'generated_at' => 'datetime',
        ];
    }
}
