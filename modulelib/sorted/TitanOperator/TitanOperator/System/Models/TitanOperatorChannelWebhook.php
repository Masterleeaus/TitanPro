<?php

namespace App\Extensions\TitanOperator\System\Models;

use Illuminate\Database\Eloquent\Model;

class TitanOperatorChannelWebhook extends Model
{
    public $timestamps = false;

    protected $table = 'ext_titan_operator_channel_webhooks';

    protected $fillable = [
        'operator_id',
        'operator_channel_id',
        'payload',
        'created_at',
    ];

    protected $casts = [
        'payload'      => 'json',
    ];
}
