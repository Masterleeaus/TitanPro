<?php

namespace App\Models\TitanNexus;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NexusConversation extends Model
{
    use BelongsToTenant;

    protected $table = 'ext_marketing_conversations';

    protected $fillable = [
        'company_id',
        'contact_id',
        'channel',
        'status',
        'last_message_at',
        'meta',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'last_message_at' => 'datetime'
    ];
}
