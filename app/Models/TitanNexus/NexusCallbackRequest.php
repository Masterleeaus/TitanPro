<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class NexusCallbackRequest extends Model
{
    protected $table = 'nexus_callback_requests';
    protected $guarded = [];
    protected $casts = [
        'payload' => 'array',
        'due_at' => 'datetime'
    ];
}
