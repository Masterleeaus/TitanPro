<?php

namespace Modules\TitanEchoAssist\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitanEchoAssist\Database\Factories\ChatbotChannelWebhookFactory;

class ChatbotChannelWebhook extends BaseModel
{
    use HasCompany;
    use HasFactory;

    protected $table = 'ext_chatbot_channel_webhooks';

    protected $fillable = [
        'company_id',
        'chatbot_id',
        'channel_id',
        'provider',
        'webhook_url',
        'verify_token',
        'secret',
        'is_active',
        'last_received_at',
        'payload',
        'chatbot_channel_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_received_at' => 'datetime',
        'payload' => 'array',
    ];

    protected static function newFactory(): ChatbotChannelWebhookFactory
    {
        return ChatbotChannelWebhookFactory::new();
    }

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id');
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(ChatbotChannel::class, 'channel_id');
    }
}

