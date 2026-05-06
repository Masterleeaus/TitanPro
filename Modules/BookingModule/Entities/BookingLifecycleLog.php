<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Model;

class BookingLifecycleLog extends Model
{
    protected $table = 'booking_lifecycle_logs';

    protected $fillable = [
        'company_id',
        'subject_type',
        'subject_id',
        'event',
        'from_status',
        'to_status',
        'actor_id',
        'payload',
        'occurred_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'occurred_at' => 'datetime',
    ];
}
