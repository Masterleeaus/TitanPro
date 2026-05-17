<?php

namespace Modules\TitanEchoAssist\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitanEchoAssist\Database\Factories\ChatbotPortalFeedbackFactory;

class ChatbotPortalFeedback extends BaseModel
{
    use HasCompany;
    use HasFactory;

    protected $table = 'ext_chatbot_portal_feedback';

    protected $fillable = [
        'company_id',
        'customer_id',
        'job_id',
        'chatbot_id',
        'session_id',
        'rating',
        'comment',
        'reclean_requested',
        'reclean_reason',
        'reclean_scheduled_at',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'reclean_requested' => 'boolean',
        'reclean_scheduled_at' => 'datetime',
    ];

    protected static function newFactory(): ChatbotPortalFeedbackFactory
    {
        return ChatbotPortalFeedbackFactory::new();
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

