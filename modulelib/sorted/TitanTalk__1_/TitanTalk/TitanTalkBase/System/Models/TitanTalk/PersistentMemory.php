<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Models\TitanTalk;

use Illuminate\Database\Eloquent\Model;

class PersistentMemory extends Model
{
    protected $table = 'titantalk_persistent_memories';

    protected $fillable = [
        'conversation_id',
        'user_id',
        'channel',
        'memory_key',
        'memory_value',
        'last_seen_at',
    ];

    protected $casts = [
        'memory_value' => 'array',
        'last_seen_at' => 'datetime',
    ];
}
