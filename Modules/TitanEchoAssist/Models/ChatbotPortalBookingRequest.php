<?php

namespace Modules\TitanEchoAssist\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotPortalBookingRequest extends Model
{
    protected $table = 'ext_chatbot_portal_booking_requests';

    protected $fillable = [
        'chatbot_id',
        'conversation_id',
        'company_id',
        'customer_id',
        'requested_date',
        'requested_time',
        'service_type',
        'notes',
        'status',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
