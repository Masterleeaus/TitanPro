<?php

namespace App\Models\TitanNexus;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NexusContact extends Model
{
    use BelongsToTenant;

    protected $table = 'ext_contacts';

    protected $fillable = [
        'company_id',
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'status',
        'meta',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
