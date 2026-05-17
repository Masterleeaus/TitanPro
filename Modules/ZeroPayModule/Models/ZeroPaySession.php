<?php

namespace Modules\ZeroPayModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;

class ZeroPaySession extends Model
{
    use SoftDeletes;

    public const STATUS_PENDING = 'pending';

    public const STATUS_OPENED = 'opened';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    public const STATUS_EXPIRED = 'expired';

    protected $table = 'zeropay_sessions';

    protected $fillable = [
        'company_id',
        'user_id',
        'session_token',
        'reference',
        'merchant_name',
        'gateway',
        'amount',
        'currency',
        'status',
        'meta',
        'expires_at',
        'opened_at',
        'completed_at',
        'failed_reason',
    ];

    public function getRouteKeyName(): string
    {
        return 'session_token';
    }

    protected $casts = [
        'meta' => 'array',
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'opened_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(ZeroPayTransaction::class, 'session_id');
    }

    public function qrCode(): HasOne
    {
        return $this->hasOne(ZeroPayQrCode::class, 'session_id');
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(ZeroPayQrCode::class, 'session_id');
    }

    public function gatewayLogs(): HasMany
    {
        return $this->hasMany(ZeroPayGatewayLog::class, 'session_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(ZeroPayNotification::class, 'session_id');
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED
            || ($this->expires_at !== null && $this->expires_at->isPast());
    }

    public function isActive(): bool
    {
        if ($this->isExpired()) {
            return false;
        }

        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_OPENED,
            self::STATUS_PROCESSING,
        ], true);
    }
}
