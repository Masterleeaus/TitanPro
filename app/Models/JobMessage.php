<?php

namespace App\Models;

use App\Contracts\TenantAware;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobMessage extends Model implements TenantAware
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'organization_id',
        'job_id',
        'customer_id',
        'channel',
        'event',
        'recipient',
        'body',
        'status',
        'error',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $jobMessage): void {
            if ($jobMessage->organization_id !== null) {
                return;
            }

            $jobMessage->organization_id = auth()->user()?->organization_id
                ?? Job::withoutGlobalScopes()->whereKey($jobMessage->job_id)->value('organization_id');
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
