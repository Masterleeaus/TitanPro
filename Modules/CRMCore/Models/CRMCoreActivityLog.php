<?php

namespace Modules\CRMCore\Models;

use Illuminate\Database\Eloquent\Model;

class CRMCoreActivityLog extends Model
{
    protected $table = 'crmcore_activity_logs';

    protected $fillable = [
        'company_id',
        'actor_id',
        'subject_type',
        'subject_id',
        'event',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
