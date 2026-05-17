<?php

namespace Modules\TitanLeads\Models;

use Illuminate\Database\Eloquent\Model;

class VoiceTranscript extends Model
{
    protected $table = 'ext_voice_transcripts';

    protected $fillable = [
        'voice_call_id',
        'transcript',
        'provider_payload',
    ];

    protected $casts = [
        'provider_payload' => 'json',
    ];
}
