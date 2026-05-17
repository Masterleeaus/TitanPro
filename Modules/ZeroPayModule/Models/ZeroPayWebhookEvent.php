<?php

namespace Modules\ZeroPayModule\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;

class ZeroPayWebhookEvent extends Model
{
    protected $table = 'zeropay_webhook_events';

    protected $fillable = [
        'company_id',
        'gateway',
        'event_type',
        'payload',
        'signature',
        'idempotency_key',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }
}
