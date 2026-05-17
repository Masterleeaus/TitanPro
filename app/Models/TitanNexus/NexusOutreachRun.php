<?php

namespace App\Models\TitanNexus;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NexusOutreachRun extends Model
{
    use BelongsToTenant;

    protected $table = 'ext_marketing_campaigns';

    protected $fillable = [
        'company_id',
        'name',
        'title',
        'channel',
        'type',
        'status',
        'offer',
        'message',
        'settings',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
