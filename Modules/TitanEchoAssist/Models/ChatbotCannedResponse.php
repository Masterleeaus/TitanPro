<?php

namespace Modules\TitanEchoAssist\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitanEchoAssist\Database\Factories\ChatbotCannedResponseFactory;

class ChatbotCannedResponse extends BaseModel
{
    use HasCompany;
    use HasFactory;

    protected $table = 'ext_chatbot_canned_responses';

    protected $fillable = [
        'company_id',
        'chatbot_id',
        'title',
        'content',
        'is_portal_friendly',
        'sort_order',
    ];

    protected $casts = [
        'is_portal_friendly' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function newFactory(): ChatbotCannedResponseFactory
    {
        return ChatbotCannedResponseFactory::new();
    }

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id');
    }
}

