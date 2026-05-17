<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class TitanNexusCampaign extends Model
{
    protected $table = 'titan_nexus_campaigns';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array'
    ];
}
