<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class NexusCallSession extends Model
{
    protected $table = 'nexus_call_sessions';
    protected $guarded = [];
    protected $casts = [
        'payload' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime'
    ];
}
