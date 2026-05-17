<?php

declare(strict_types=1);

namespace Modules\Budgeting\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReimbursementBatch extends BaseModel
{
    use HasCompany;
    use SoftDeletes;

    protected $table = 'budget_reimbursement_batches';

    protected $fillable = [
        'company_id',
        'reference',
        'status',
        'total_amount',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function reimbursements(): HasMany
    {
        return $this->hasMany(Reimbursement::class, 'batch_id');
    }
}
