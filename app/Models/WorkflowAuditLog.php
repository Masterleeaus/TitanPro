<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Append-only audit record for each step transition in a workflow instance.
 */
class WorkflowAuditLog extends Model
{
    use HasFactory;
    /** Table has only created_at. */
    const UPDATED_AT = null;

    protected $table = 'titan_workflow_audit';

    protected $fillable = [
        'workflow_instance_id',
        'company_id',
        'step_key',
        'step_type',
        'outcome',
        'actor',
        'payload',
        'error',
        'duration_ms',
    ];

    protected $casts = [
        'payload'    => 'array',
        'created_at' => 'datetime',
    ];

    public function workflowInstance(): BelongsTo
    {
        return $this->belongsTo(WorkflowInstance::class, 'workflow_instance_id');
    }

    /** Prevent accidental updates. */
    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new \LogicException('WorkflowAuditLog records are append-only and cannot be updated.');
        }

        return parent::save($options);
    }

    /** Prevent accidental deletes. */
    public function delete(): ?bool
    {
        throw new \LogicException('WorkflowAuditLog records are append-only and cannot be deleted.');
    }
}
