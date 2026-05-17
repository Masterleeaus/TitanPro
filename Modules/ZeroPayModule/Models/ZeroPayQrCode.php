<?php

namespace Modules\ZeroPayModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;

class ZeroPayQrCode extends Model
{
    use SoftDeletes;

    protected $table = 'zeropay_qr_codes';

    protected $fillable = [
        'company_id',
        'session_id',
        'pay_id',
        'merchant_name',
        'amount',
        'currency',
        'reference',
        'session_token',
        'payload',
        'expiry_timestamp',
        'qr_image_path',
        'status',
    ];

    protected $casts = [
        'payload' => 'array',
        'amount' => 'decimal:2',
        'expiry_timestamp' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }
}
