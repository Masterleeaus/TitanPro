<?php

namespace App\Extensions\TitanOperator\System\Models;

use Illuminate\Database\Eloquent\Model;

class TitanOperatorChannel extends Model
{
    protected $table = 'ext_titan_operator_channels';

    protected $fillable = [
        'user_id',
        'operator_id',
        'channel',
        'credentials',
        'payload',
        'connected_at',
    ];

    protected $casts = [
        'credentials'  => 'json',
        'payload'      => 'json',
        'connected_at' => 'datetime',
    ];

    public function isSandbox(): bool
    {
        return data_get($this->credentials, 'whatsapp_environment') === 'sandbox';
    }
}
