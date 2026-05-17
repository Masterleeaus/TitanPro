<?php

declare(strict_types=1);

namespace Modules\Budgeting\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetActual extends BaseModel
{
    use HasCompany;
    use SoftDeletes;

    protected $table = 'budget_actuals';

    protected $fillable = [
        'company_id',
        'period_start',
        'period_end',
        'budget_allocation_id',
        'category_id',
        'planned_amount',
        'actual_amount',
        'locked_at',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'planned_amount' => 'decimal:2',
            'actual_amount' => 'decimal:2',
            'locked_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function variance(): HasOne
    {
        return $this->hasOne(BudgetVariance::class, 'actual_id');
    }
}
