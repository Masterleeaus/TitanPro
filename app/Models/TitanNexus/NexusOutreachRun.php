<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class NexusOutreachRun extends Model
{
    protected $table = 'ext_marketing_campaigns';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array'
    ];
}
