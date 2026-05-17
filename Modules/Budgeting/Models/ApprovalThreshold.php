<?php

declare(strict_types=1);

namespace Modules\Budgeting\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalThreshold extends BaseModel
{
    use HasCompany;
    use SoftDeletes;

    protected $table = 'budget_approval_thresholds';

    protected $fillable = [
        'company_id',
        'name',
        'min_amount',
        'max_amount',
        'approver_role',
        'chain',
        'escalation_hours',
    ];

    protected function casts(): array
    {
        return [
            'min_amount' => 'decimal:2',
            'max_amount' => 'decimal:2',
            'chain' => 'array',
            'escalation_hours' => 'integer',
        ];
    }
}
