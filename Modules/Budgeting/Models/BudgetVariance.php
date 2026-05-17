<?php

declare(strict_types=1);

namespace Modules\Budgeting\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetVariance extends BaseModel
{
    use HasCompany;
    use SoftDeletes;

    protected $table = 'budget_variances';

    protected $fillable = [
        'company_id',
        'actual_id',
        'variance_amount',
        'variance_pct',
        'flag',
        'notes',
        'anomaly_flagged',
    ];

    protected function casts(): array
    {
        return [
            'variance_amount' => 'decimal:2',
            'variance_pct' => 'decimal:4',
            'anomaly_flagged' => 'boolean',
        ];
    }

    public function actual(): BelongsTo
    {
        return $this->belongsTo(BudgetActual::class, 'actual_id');
    }
}
