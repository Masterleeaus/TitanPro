<?php

namespace Modules\TitanEchoAssist\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitanEchoAssist\Database\Factories\ChatbotPortalSiteProfileFactory;

class ChatbotPortalSiteProfile extends BaseModel
{
    use HasCompany;
    use HasFactory;

    protected $table = 'ext_chatbot_portal_site_profiles';

    protected $fillable = [
        'company_id',
        'customer_id',
        'property_id',
        'alarm_code',
        'alarm_instructions',
        'pets',
        'parking',
        'access_method',
        'priority_rooms',
        'special_instructions',
        'last_updated_at',
    ];

    protected $casts = [
        'alarm_code' => 'encrypted',
        'pets' => 'array',
        'priority_rooms' => 'array',
        'last_updated_at' => 'datetime',
    ];

    protected static function newFactory(): ChatbotPortalSiteProfileFactory
    {
        return ChatbotPortalSiteProfileFactory::new();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(ChatbotCustomer::class, 'customer_id');
    }
}

