<?php

namespace App\Models\TitanNexus;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NexusCallEvent extends Model
{
    use BelongsToTenant;

    protected $table = 'nexus_call_events';

    protected $fillable = [
        'company_id',
        'call_session_id',
        'provider',
        'provider_event_id',
        'event_type',
        'status',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
