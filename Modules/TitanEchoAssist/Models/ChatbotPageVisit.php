<?php

namespace Modules\TitanEchoAssist\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitanEchoAssist\Database\Factories\ChatbotPageVisitFactory;

class ChatbotPageVisit extends BaseModel
{
    use HasCompany;
    use HasFactory;

    protected $table = 'ext_chatbot_page_visits';

    protected $fillable = [
        'company_id',
        'chatbot_id',
        'session_id',
        'visitor_id',
        'page_url',
        'page_title',
        'referrer',
        'duration_seconds',
        'visited_at',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'visited_at' => 'datetime',
    ];

    protected static function newFactory(): ChatbotPageVisitFactory
    {
        return ChatbotPageVisitFactory::new();
    }

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id');
    }
}

