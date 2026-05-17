<?php

namespace App\Models\TitanNexus;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NexusCallSession extends Model
{
    use BelongsToTenant;

    protected $table = 'nexus_call_sessions';

    protected $fillable = [
        'company_id',
        'provider',
        'provider_call_id',
        'direction',
        'lead_id',
        'contact_id',
        'from_number',
        'to_number',
        'status',
        'outcome',
        'duration_seconds',
        'summary',
        'transcript',
        'payload',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime'
    ];
}
