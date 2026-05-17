<?php

namespace App\Models\TitanNexus;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NexusCallbackRequest extends Model
{
    use BelongsToTenant;

    protected $table = 'nexus_callback_requests';

    protected $fillable = [
        'company_id',
        'lead_id',
        'contact_id',
        'call_session_id',
        'name',
        'phone',
        'status',
        'priority',
        'due_at',
        'notes',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'due_at' => 'datetime'
    ];
}
