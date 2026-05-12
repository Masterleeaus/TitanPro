<?php

namespace Modules\TitanLeads\Models;

use Illuminate\Database\Eloquent\Model;

class OutboxDraft extends Model
{
    protected $table = 'ext_outbox_drafts';

    protected $fillable = [
        'user_id',
        'conversation_id',
        'channel',
        'to',
        'subject',
        'body',
        'payload',
        'status',
        'is_ai_generated',
        'requires_approval',
        'approved_at',
        'sent_at',
        'last_error',
    ];

    protected $casts = [
        'payload' => 'json',
        'is_ai_generated' => 'boolean',
        'requires_approval' => 'boolean',
        'approved_at' => 'datetime',
        'sent_at' => 'datetime',
    ];
}
