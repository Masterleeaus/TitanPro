<?php

namespace App\Extensions\TitanOperator\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TitanOperatorConversation extends Model
{
    protected $table = 'ext_titan_operator_conversations';

    protected $fillable = [
        'operator_customer_id',
        'operator_channel',
        'operator_channel_id',
        'customer_channel_id',
        'ip_address',
        'conversation_name',
        'operator_id',
        'session_id',
        'connect_agent_at',
        'customer_payload',
        'is_showed_on_history',
        'ticket_status',
        'country_code',
        'pinned',
        'last_activity_at',
        'send_email_at',
    ];

    protected $casts = [
        'operator_id'           => 'integer',
        'session_id'           => 'string',
        'customer_payload'     => 'json',
        'is_showed_on_history' => 'boolean',
        'last_activity_at'	    => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(TitanOperatorCustomer::class, 'operator_customer_id');
    }

    public function operatorChannel(): BelongsTo
    {
        return $this->belongsTo(TitanOperatorChannel::class, 'operator_channel_id');
    }

    public function sessionId(): string
    {
        return $this->session_id;
    }

    public function titan_operator(): BelongsTo
    {
        return $this->belongsTo(TitanOperator::class);
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(TitanOperatorHistory::class, 'conversation_id')
            ->where('role', 'user')
            ->orderByDesc('id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(TitanOperatorHistory::class, 'conversation_id');
    }
}
