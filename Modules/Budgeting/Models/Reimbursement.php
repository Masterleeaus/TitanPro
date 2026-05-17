<?php

declare(strict_types=1);

namespace Modules\Budgeting\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reimbursement extends BaseModel
{
    use HasCompany;
    use SoftDeletes;

    protected $table = 'budget_reimbursements';

    protected $fillable = [
        'company_id',
        'batch_id',
        'expense_id',
        'user_id',
        'amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ReimbursementBatch::class, 'batch_id');
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
