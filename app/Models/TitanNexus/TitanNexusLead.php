<?php

namespace App\Models\TitanNexus;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class TitanNexusLead extends Model
{
    use BelongsToTenant;

    protected $table = 'titan_nexus_leads';

    protected $fillable = [
        'company_id',
        'tenant_id',
        'campaign_id',
        'name',
        'email',
        'phone',
        'company',
        'score',
        'status',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
