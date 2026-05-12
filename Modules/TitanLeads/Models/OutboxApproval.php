<?php

namespace Modules\TitanLeads\Models;

use Illuminate\Database\Eloquent\Model;

class OutboxApproval extends Model
{
    protected $table = 'ext_outbox_approvals';

    protected $fillable = [
        'draft_id',
        'approver',
        'approval_status',
        'approval_token',
        'approval_payload',
        'decided_at',
    ];

    protected $casts = [
        'approval_payload' => 'json',
        'decided_at' => 'datetime',
    ];
}
