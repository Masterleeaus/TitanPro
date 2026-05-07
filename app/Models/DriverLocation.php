<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverLocation extends Model
{
    protected $fillable = [
        'organization_id',
        'user_id',
        'latitude',
        'longitude',
        'heading',
        'speed',
        'recorded_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $driverLocation): void {
            if ($driverLocation->organization_id !== null) {
                return;
            }

            $driverLocation->organization_id = auth()->user()?->organization_id
                ?? User::query()
                    ->whereKey($driverLocation->user_id)
                    ->value('organization_id');
        });
    }

    protected function casts(): array
    {
        return [
            'latitude'    => 'decimal:7',
            'longitude'   => 'decimal:7',
            'heading'     => 'decimal:2',
            'speed'       => 'decimal:2',
            'recorded_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function scopeForOrganization(Builder $query, ?int $organizationId): Builder
    {
        if ($organizationId === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('organization_id', $organizationId);
    }
}
