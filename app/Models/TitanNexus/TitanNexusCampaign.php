<?php

namespace App\Models\TitanNexus;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class TitanNexusCampaign extends Model
{
    use BelongsToTenant;

    protected $table = 'titan_nexus_campaigns';

    protected $fillable = [
        'company_id',
        'tenant_id',
        'name',
        'vertical',
        'status',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
