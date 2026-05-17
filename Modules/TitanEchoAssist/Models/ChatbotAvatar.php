<?php

namespace Modules\TitanEchoAssist\Models;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitanEchoAssist\Database\Factories\ChatbotAvatarFactory;

class ChatbotAvatar extends BaseModel
{
    use HasCompany;
    use HasFactory;

    protected $table = 'ext_chatbot_avatars';

    protected $fillable = [
        'company_id',
        'chatbot_id',
        'file_path',
        'file_name',
        'mime_type',
        'size',
        'is_default',
        'avatar',
        'user_id',
    ];

    protected $casts = [
        'size' => 'integer',
        'is_default' => 'boolean',
    ];

    protected static function newFactory(): ChatbotAvatarFactory
    {
        return ChatbotAvatarFactory::new();
    }

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id');
    }
}

