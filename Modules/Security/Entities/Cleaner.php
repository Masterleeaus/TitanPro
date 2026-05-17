<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cleaner extends BaseModel
{
    use HasCompany;

    protected $table = 'security_cleaners';

    protected $guarded = ['id'];

    protected $casts = [
        'meta' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'suspended_at' => 'datetime',
        'access_expires_at' => 'datetime',
        'last_check_in_at' => 'datetime',
        'last_check_out_at' => 'datetime',
    ];


    public function site(): BelongsTo
    {
        return $this->belongsTo(CleanerSite::class, 'site_id');
    }

    public function siteLogs(): HasMany
    {
        return $this->hasMany(CleanerSiteLog::class, 'cleaner_id');
    }

    public function accessCards(): HasMany
    {
        return $this->hasMany(TrAccessCard::class, 'cleaner_id');
    }

    public function workPermits(): HasMany
    {
        return $this->hasMany(WorkPermits::class, 'cleaner_id');
    }

    public function goodsMovements(): HasMany
    {
        return $this->hasMany(TrInOutPermit::class, 'cleaner_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isApproved(): bool
    {
        return $this->status === 'active' && $this->approved_at !== null && ! $this->isAccessExpired();
    }

    public function isAccessExpired(): bool
    {
        return $this->access_expires_at !== null && $this->access_expires_at->isPast();
    }

    public function isOnSite(): bool
    {
        return $this->last_check_in_at !== null
            && ($this->last_check_out_at === null || $this->last_check_in_at->greaterThan($this->last_check_out_at));
    }
}

