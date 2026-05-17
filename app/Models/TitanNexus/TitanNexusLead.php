<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class TitanNexusLead extends Model
{
    protected $table = 'titan_nexus_leads';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array'
    ];
}
