<?php

namespace App\Models\TitanNexus;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NexusCallRecording extends Model
{
    use BelongsToTenant;

    protected $table = 'nexus_call_recordings';

    protected $fillable = [
        'company_id',
        'call_session_id',
        'provider',
        'provider_recording_id',
        'recording_url',
        'local_path',
        'duration_seconds',
        'status',
        'fetched_at',
        'expires_at',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'fetched_at' => 'datetime',
        'expires_at' => 'datetime'
    ];
}
