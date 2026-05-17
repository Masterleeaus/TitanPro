<?php

namespace App\Models\TitanNexus;

use Illuminate\Database\Eloquent\Model;

class NexusContractDocument extends Model
{
    protected $table = 'nexus_contract_documents';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array'
    ];
}
