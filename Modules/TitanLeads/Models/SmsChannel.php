<?php

namespace Modules\TitanLeads\Models;

use Illuminate\Database\Eloquent\Model;

class SmsChannel extends Model
{
    protected $table = 'ext_sms_channels';

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
