<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class NexusContact extends Model
{
    protected $table = 'ext_contacts';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array'
    ];
}
