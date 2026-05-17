<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class NexusConversation extends Model
{
    protected $table = 'ext_marketing_conversations';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'last_message_at' => 'datetime'
    ];
}
