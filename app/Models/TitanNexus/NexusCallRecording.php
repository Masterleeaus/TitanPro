<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class NexusCallRecording extends Model
{
    protected $table = 'nexus_call_recordings';
    protected $guarded = [];
    protected $casts = [
        'payload' => 'array',
        'fetched_at' => 'datetime',
        'expires_at' => 'datetime'
    ];
}
