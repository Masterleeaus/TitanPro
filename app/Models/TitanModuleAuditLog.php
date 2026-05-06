<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Append-only audit log for module lifecycle actions (sync / enable / disable).
 *
 * This model intentionally disables updates and deletes to enforce the
 * append-only contract.
 */
class TitanModuleAuditLog extends Model
{
    /** The table has only created_at, no updated_at. */
    const UPDATED_AT = null;

    protected $table = 'titan_module_audit_log';

    protected $fillable = [
        'actor',
        'action',
        'module',
        'outcome',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
        'created_at' => 'datetime',
    ];

    /** Prevent accidental updates. */
    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new \LogicException('TitanModuleAuditLog records are append-only and cannot be updated.');
        }

        return parent::save($options);
    }

    /** Prevent accidental deletes. */
    public function delete(): ?bool
    {
        throw new \LogicException('TitanModuleAuditLog records are append-only and cannot be deleted.');
    }
}
