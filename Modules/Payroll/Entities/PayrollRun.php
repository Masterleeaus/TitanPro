<?php

namespace Modules\Payroll\Entities;

use App\Models\BaseModel;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollRun extends BaseModel
{
    protected $guarded = ['id'];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'gross_total' => 'decimal:2',
        'net_total' => 'decimal:2',
        'deduction_total' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(PayrollRunApproval::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(PayrollAuditLog::class);
    }
}
