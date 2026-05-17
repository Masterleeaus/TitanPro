<?php

declare(strict_types=1);

namespace Modules\Budgeting\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends BaseModel
{
    use HasCompany;
    use SoftDeletes;

    protected $table = 'budget_receipts';

    protected $fillable = [
        'company_id',
        'expense_id',
        'file_path',
        'file_name',
        'mime_type',
        'ocr_status',
        'ocr_data',
        'extracted_amount',
        'extracted_date',
        'extracted_vendor',
    ];

    protected function casts(): array
    {
        return [
            'ocr_data' => 'array',
            'extracted_amount' => 'decimal:2',
            'extracted_date' => 'date',
        ];
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }
}
