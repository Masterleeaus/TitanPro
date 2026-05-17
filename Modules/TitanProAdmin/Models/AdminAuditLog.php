<?php

namespace Modules\TitanProAdmin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAuditLog extends Model
{
    const UPDATED_AT = null;

    protected $table = 'titan_admin_audit_logs';

    protected $fillable = [
        'actor_id',
        'target_company_id',
        'action',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
        'created_at' => 'datetime',
    ];
}
