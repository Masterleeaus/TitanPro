<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Models\TitanTalk;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Handoff extends Model
{
    protected $table = 'titantalk_handoffs';

    protected $fillable = [
        'conversation_id',
        'company_id',
        'channel',
        'intent',
        'state',
        'priority',
        'goal',
        'reason',
        'context',
        'assigned_to_user_id',
        'requested_at',
        'assigned_at',
        'resolved_at',
    ];

    protected $casts = [
        'context' => 'array',
        'requested_at' => 'datetime',
        'assigned_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(MarketingConversation::class, 'conversation_id');
    }
}
