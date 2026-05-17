<?php

namespace Modules\ZeroPayModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;

class ZeroPayTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'zeropay_transactions';

    protected $fillable = [
        'company_id',
        'session_id',
        'user_id',
        'type',
        'gateway',
        'gateway_reference',
        'amount',
        'currency',
        'status',
        'fee',
        'net_amount',
        'payer_name',
        'payee_name',
        'reference',
        'failure_reason',
        'gateway_response',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'gateway_response' => 'array',
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }

    public function gatewayLogs(): HasMany
    {
        return $this->hasMany(ZeroPayGatewayLog::class, 'transaction_id');
    }
}
