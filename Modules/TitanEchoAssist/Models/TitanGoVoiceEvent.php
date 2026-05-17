<?php

namespace Modules\TitanEchoAssist\Models;

use Illuminate\Database\Eloquent\Model;

class TitanGoVoiceEvent extends Model
{
    protected $table = 'titango_voice_events';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'user_id',
        'job_id',
        'action_key',
        'transcript',
        'matched_phrase',
        'status',
        'result',
        'duration_ms',
    ];

    protected $casts = [
        'result' => 'array',
        'duration_ms' => 'integer',
    ];
}
