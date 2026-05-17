<?php

namespace App\Models\TitanNexus;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NexusVoiceCallLog extends Model
{
    use BelongsToTenant;

    protected $table = 'nexus_voice_call_logs';

    protected $fillable = [
        'company_id',
        'provider',
        'direction',
        'call_id',
        'lead_id',
        'contact_id',
        'phone_number',
        'status',
        'duration_seconds',
        'summary',
        'transcript',
        'recording_url',
        'request_payload',
        'response_payload',
        'webhook_payload',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'webhook_payload' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
}
