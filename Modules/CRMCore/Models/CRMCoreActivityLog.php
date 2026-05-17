<?php

namespace Modules\CRMCore\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\CRMCore\Events\ActivityLogged;
use Modules\CRMCore\Traits\UsesScopedByCompany;

class CRMCoreActivityLog extends Model
{
    use UsesScopedByCompany;

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

    protected static function booted(): void
    {
        static::created(static function (self $log): void {
            ActivityLogged::dispatch($log);
        });
    }
}
