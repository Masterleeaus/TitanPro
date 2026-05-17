<?php

namespace Modules\TitanEchoAssist\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitanEchoAssist\Database\Factories\ChatbotPortalAutomationLogFactory;

class ChatbotPortalAutomationLog extends BaseModel
{
    use HasCompany;
    use HasFactory;

    protected $table = 'ext_chatbot_portal_automation_logs';

    protected $fillable = [
        'company_id',
        'chatbot_id',
        'trigger_event',
        'trigger_payload',
        'action_taken',
        'action_payload',
        'outcome',
        'processed_at',
    ];

    protected $casts = [
        'trigger_payload' => 'array',
        'action_payload' => 'array',
        'processed_at' => 'datetime',
    ];

    protected static function newFactory(): ChatbotPortalAutomationLogFactory
    {
        return ChatbotPortalAutomationLogFactory::new();
    }

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id');
    }
}
