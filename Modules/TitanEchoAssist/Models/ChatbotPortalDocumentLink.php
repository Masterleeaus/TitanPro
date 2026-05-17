<?php

namespace Modules\TitanEchoAssist\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitanEchoAssist\Database\Factories\ChatbotPortalDocumentLinkFactory;

class ChatbotPortalDocumentLink extends BaseModel
{
    use HasCompany;
    use HasFactory;

    protected $table = 'ext_chatbot_portal_document_links';

    protected $fillable = [
        'company_id',
        'customer_id',
        'chatbot_id',
        'document_type',
        'title',
        'file_path',
        'external_url',
        'expires_at',
        'is_signed',
        'signed_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_signed' => 'boolean',
        'signed_at' => 'datetime',
    ];

    protected static function newFactory(): ChatbotPortalDocumentLinkFactory
    {
        return ChatbotPortalDocumentLinkFactory::new();
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

