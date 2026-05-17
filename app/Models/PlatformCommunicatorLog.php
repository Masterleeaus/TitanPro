<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformCommunicatorLog extends Model
{
    protected $table = 'platform_communicator_logs';

    protected $fillable = [
        'channel',
        'audience',
        'subject',
        'message',
        'status',
        'recipient_count',
        'metadata',
        'sent_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'sent_at' => 'datetime',
    ];
}
