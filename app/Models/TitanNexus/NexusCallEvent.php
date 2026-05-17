<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class NexusCallEvent extends Model
{
    protected $table = 'nexus_call_events';
    protected $guarded = [];
    protected $casts = [
        'payload' => 'array'
    ];
}
