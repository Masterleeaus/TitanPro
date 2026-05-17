<?php

namespace App\Models\TitanNexus;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NexusBookingHandoff extends Model
{
    use BelongsToTenant;

    protected $table = 'nexus_booking_handoffs';

    protected $fillable = [
        'company_id',
        'lead_id',
        'status',
        'scheduled_at',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'scheduled_at' => 'datetime'
    ];
}
