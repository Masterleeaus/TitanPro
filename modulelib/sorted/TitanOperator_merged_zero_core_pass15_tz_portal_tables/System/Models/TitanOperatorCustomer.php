<?php

namespace App\Extensions\TitanOperator\System\Models;

use Illuminate\Database\Eloquent\Model;

class TitanOperatorCustomer extends Model
{
    protected $table = 'tz_portal_operator_customers';

    protected $fillable = [
        'user_id',
        'avatar',
        'name',
        'email',
        'phone',
        'operator_id',
        'session_id',
        'country_code',
        'ip_address',
        'operator_channel',
        'enabled_sound',
        'payload',
    ];

    protected $casts = [
        'payload' => 'json',
    ];
}