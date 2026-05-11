<?php

namespace App\Extensions\TitanLeads\System\Models;

use Illuminate\Database\Eloquent\Model;

class VoiceCall extends Model
{
    protected $table = 'ext_voice_calls';

    protected $fillable = [
        'user_id',
        'conversation_id',
        'call_sid',
        'from_number',
        'to_number',
        'direction',
        'status',
        'duration',
        'recording_url',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
}
