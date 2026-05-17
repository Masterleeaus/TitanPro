<?php

namespace Modules\TitanEchoAssist\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitanEchoAssist\Database\Factories\ChatbotPortalNotificationFactory;

class ChatbotPortalNotification extends BaseModel
{
    use HasCompany;
    use HasFactory;

    protected $table = 'ext_chatbot_portal_notifications';

    protected $fillable = [
        'company_id',
        'customer_id',
        'chatbot_id',
        'event_type',
        'title',
        'body',
        'action_url',
        'is_read',
        'read_at',
        'sent_at',
        'channel',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    protected static function newFactory(): ChatbotPortalNotificationFactory
    {
        return ChatbotPortalNotificationFactory::new();
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

