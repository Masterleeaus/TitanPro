<?php

namespace Modules\ZeroPayModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;

class ZeroPayBankAccount extends Model
{
    use SoftDeletes;

    protected $table = 'zeropay_bank_accounts';

    protected $fillable = [
        'company_id',
        'account_name',
        'bsb',
        'account_number',
        'pay_id',
        'bank_name',
        'status',
        'is_default',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'is_default' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(ZeroPayBankDeposit::class, 'bank_account_id');
    }
}
