<?php

namespace Modules\CallingAgent\Models;

use Illuminate\Database\Eloquent\Model;

class CallingAgentConfig extends Model
{
    protected $table = 'calling_agent_configs';

    protected $guarded = [];

    protected $casts = [
        'twilio_auth_token' => 'encrypted',
        'elevenlabs_api_key' => 'encrypted',
        'sip_password' => 'encrypted',
    ];
}
