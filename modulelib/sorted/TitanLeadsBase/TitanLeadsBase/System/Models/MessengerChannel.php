<?php

namespace App\Extensions\TitanLeads\System\Models;

use Illuminate\Database\Eloquent\Model;

class MessengerChannel extends Model
{
    protected $table = 'ext_messenger_channels';

    protected $fillable = [
        'company_id',
        'provider',
        'page_id',
        'access_token',
        'verify_token',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
