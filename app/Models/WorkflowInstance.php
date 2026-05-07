<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Represents a single running (or completed) workflow execution.
 *
 * Statuses:
 *   pending   — created, not yet started
 *   running   — engine is actively processing a step
 *   waiting   — paused on a wait/approval step
 *   completed — all steps finished successfully
 *   failed    — terminal failure after exhausting retries
 *   cancelled — manually cancelled
 */
class WorkflowInstance extends Model
{
    use HasFactory;
    protected $table = 'titan_workflow_instances';

    protected $fillable = [
        'company_id',
        'workflow_id',
        'workflow_version',
        'entity_type',
        'entity_id',
        'status',
        'current_step',
        'context',
        'attempt',
        'initiated_by',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'context'      => 'array',
        'attempt'      => 'integer',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function auditLogs(): HasMany
    {
        return $this->hasMany(WorkflowAuditLog::class, 'workflow_instance_id');
    }

    /** Convenience: is the instance still active? */
    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'running', 'waiting'], true);
    }
}
