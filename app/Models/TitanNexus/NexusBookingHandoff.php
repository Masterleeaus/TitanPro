<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class NexusBookingHandoff extends Model
{
    protected $table = 'nexus_booking_handoffs';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'scheduled_at' => 'datetime'
    ];
}
