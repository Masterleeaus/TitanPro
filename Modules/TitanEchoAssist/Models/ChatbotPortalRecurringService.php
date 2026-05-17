<?php

namespace Modules\TitanEchoAssist\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitanEchoAssist\Database\Factories\ChatbotPortalRecurringServiceFactory;

class ChatbotPortalRecurringService extends BaseModel
{
    use HasCompany;
    use HasFactory;

    protected $table = 'ext_chatbot_portal_recurring_services';

    protected $fillable = [
        'company_id',
        'chatbot_id',
        'customer_id',
        'job_id',
        'frequency',
        'is_paused',
        'pause_until',
        'skip_next',
        'permanent_extras',
        'notes',
    ];

    protected $casts = [
        'is_paused' => 'boolean',
        'pause_until' => 'date',
        'skip_next' => 'boolean',
        'permanent_extras' => 'array',
    ];

    protected static function newFactory(): ChatbotPortalRecurringServiceFactory
    {
        return ChatbotPortalRecurringServiceFactory::new();
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

