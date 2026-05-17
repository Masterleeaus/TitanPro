<?php

namespace Modules\TitanOperator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversation extends Model
{
    protected $table = 'tz_portal_operator_conversations';

    protected $fillable = [
        'operator_id',
        'session_id',
        'operator_channel_id',
        'chatbot_customer_id',
        'last_activity_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }
}
