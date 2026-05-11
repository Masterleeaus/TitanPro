<?php

namespace App\Extensions\TitanLeads\System\Models;

use Illuminate\Database\Eloquent\Model;

class VoiceChannel extends Model
{
    protected $table = 'ext_voice_channels';

    protected $fillable = [
        'user_id',
        'provider',
        'account_sid',
        'auth_token',
        'from_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
