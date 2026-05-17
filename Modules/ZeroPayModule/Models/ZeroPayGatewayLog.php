<?php

namespace Modules\ZeroPayModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;

class ZeroPayGatewayLog extends Model
{
    protected $table = 'zeropay_gateway_logs';

    protected $fillable = [
        'company_id',
        'session_id',
        'transaction_id',
        'gateway',
        'direction',
        'event',
        'request_payload',
        'response_payload',
        'http_status',
        'duration_ms',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(ZeroPayTransaction::class, 'transaction_id');
    }
}
