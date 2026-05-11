<?php

namespace App\Extensions\TitanLeads\System\Models;

use Illuminate\Database\Eloquent\Model;

class EmailChannel extends Model
{
    protected $table = 'ext_email_channels';

    protected $fillable = [
        'user_id',
        'provider',
        'from_email',
        'from_name',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
