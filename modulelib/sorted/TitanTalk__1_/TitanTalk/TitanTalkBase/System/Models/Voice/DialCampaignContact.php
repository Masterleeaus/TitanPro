<?php

namespace App\Extensions\MarketingBot\System\Models\Voice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DialCampaignContact extends Model
{
    protected $table = 'titantalk_dial_campaign_contacts';

    protected $fillable = [
        'campaign_id',
        'name',
        'phone_number',
        'meta',
        'attempt_count',
        'last_attempt_at',
        'status', // pending | calling | answered | failed | do_not_call
    ];

    protected $casts = [
        'attempt_count' => 'integer',
        'last_attempt_at' => 'datetime',
        'meta' => 'array',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(DialCampaign::class, 'campaign_id');
    }
}
