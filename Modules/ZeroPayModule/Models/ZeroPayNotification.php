<?php

namespace Modules\ZeroPayModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;
use Modules\ZeroPayModule\ValueObjects\NotificationEventType;

class ZeroPayNotification extends Model
{
    protected $table = 'zeropay_notifications';

    protected $fillable = [
        'company_id',
        'user_id',
        'session_id',
        'event',
        'channel',
        'payload',
        'status',
        'sent_at',
        'read_at',
    ];

    protected $casts = [
        'event' => NotificationEventType::class,
        'payload' => 'array',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * Virtual alias: `event_type` reads and writes the `event` attribute.
     */
    public function getEventTypeAttribute(): ?NotificationEventType
    {
        return $this->event;
    }

    public function setEventTypeAttribute(NotificationEventType $value): void
    {
        $this->event = $value;
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }
}
