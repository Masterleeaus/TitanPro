<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitanUsageMeter extends Model
{
    protected $fillable = [
        'organization_id',
        'meter_key',
        'period',
        'count',
        'reset_at',
    ];

    protected $casts = [
        'count'    => 'integer',
        'reset_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Retrieve the current-period meter for an org + meter key, or null if none exists.
     */
    public static function currentFor(int $organizationId, string $meterKey): ?self
    {
        $period = now()->format('Y-m');

        return static::where('organization_id', $organizationId)
            ->where('meter_key', $meterKey)
            ->where('period', $period)
            ->first();
    }

    /**
     * Current usage count for an org + meter key in the active period.
     */
    public static function countFor(int $organizationId, string $meterKey): int
    {
        return static::currentFor($organizationId, $meterKey)?->count ?? 0;
    }
}
