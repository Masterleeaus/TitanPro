<?php

namespace App\Extensions\MarketingBot\System\Models\Voice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DialCampaign extends Model
{
    protected $table = 'titantalk_dial_campaigns';

    protected $fillable = [
        'company_id',
        'name',
        'from_number',
        'max_attempts',
        'retry_minutes',
        'enabled',
        'status', // draft | running | paused | finished
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'max_attempts' => 'integer',
        'retry_minutes' => 'integer',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(DialCampaignContact::class, 'campaign_id');
    }
}
