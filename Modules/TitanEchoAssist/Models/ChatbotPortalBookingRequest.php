<?php

namespace Modules\TitanEchoAssist\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitanEchoAssist\Database\Factories\ChatbotPortalBookingRequestFactory;

class ChatbotPortalBookingRequest extends BaseModel
{
    use HasCompany;
    use HasFactory;

    protected $table = 'ext_chatbot_portal_booking_requests';

    protected $fillable = [
        'company_id',
        'chatbot_id',
        'customer_id',
        'session_id',
        'requested_at',
        'preferred_date',
        'preferred_time',
        'service_type',
        'notes',
        'status',
        'confirmed_job_id',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'preferred_date' => 'date',
        'preferred_time' => 'string',
    ];

    protected static function newFactory(): ChatbotPortalBookingRequestFactory
    {
        return ChatbotPortalBookingRequestFactory::new();
    }

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(ChatbotCustomer::class, 'customer_id');
    }
}
