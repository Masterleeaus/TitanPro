<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class NexusVoiceCallLog extends Model
{
    protected $table = 'nexus_voice_call_logs';

    protected $guarded = [];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'webhook_payload' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
}
