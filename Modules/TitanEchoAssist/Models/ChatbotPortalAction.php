<?php

namespace Modules\TitanEchoAssist\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitanEchoAssist\Database\Factories\ChatbotPortalActionFactory;

class ChatbotPortalAction extends BaseModel
{
    use HasCompany;
    use HasFactory;

    protected $table = 'ext_chatbot_portal_actions';

    protected $fillable = [
        'company_id',
        'customer_id',
        'chatbot_id',
        'session_id',
        'action_type',
        'payload',
        'status',
        'result',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'result' => 'array',
        'processed_at' => 'datetime',
    ];

    protected static function newFactory(): ChatbotPortalActionFactory
    {
        return ChatbotPortalActionFactory::new();
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

