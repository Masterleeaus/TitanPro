<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class NexusTrainingContent extends Model
{
    protected $table = 'nexus_training_contents';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array'
    ];
}
