<?php

namespace App\Models\TitanNexus;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NexusContractDocument extends Model
{
    use BelongsToTenant;

    protected $table = 'nexus_contract_documents';

    protected $fillable = [
        'company_id',
        'title',
        'vertical',
        'status',
        'body',
        'content',
        'document_type',
        'meta',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
