<?php

namespace App\Platform\Workflows;

use App\Models\WorkflowAuditLog;
use App\Models\WorkflowInstance;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\DB;

/**
 * Persists workflow state transitions to `titan_workflow_instances` and
 * appends an audit record to `titan_workflow_audit`.
 *
 * All writes are wrapped in a database transaction so a failed audit write
 * never leaves the instance in an inconsistent state.
 */
class TransitionEngine
{
    public function __construct(private readonly Guard $auth) {}

    /**
     * Move a workflow instance to a new step and status, writing an audit record.
     *
     * @param array<string, mixed> $payload   Optional snapshot data for the audit record.
     */
    public function transition(
        WorkflowInstance $instance,
        string $toStep,
        string $outcome,
        string $stepType = 'action',
        array $payload = [],
        ?string $error = null,
        ?int $durationMs = null,
    ): void {
        DB::transaction(function () use ($instance, $toStep, $outcome, $stepType, $payload, $error, $durationMs): void {
            // Derive the next instance status from the outcome
            $newStatus = $this->deriveStatus($outcome);

            $instance->current_step = $toStep;
            $instance->status       = $newStatus;

            if ($newStatus === 'running' && $instance->started_at === null) {
                $instance->started_at = now();
            }

            if (in_array($newStatus, ['completed', 'failed', 'cancelled'], true)) {
                $instance->completed_at = now();
            }

            $instance->save();

            WorkflowAuditLog::create([
                'workflow_instance_id' => $instance->id,
                'company_id'           => $instance->company_id,
                'step_key'             => $toStep,
                'step_type'            => $stepType,
                'outcome'              => $outcome,
                'actor'                => $this->resolveActor(),
                'payload'              => $payload ?: null,
                'error'                => $error,
                'duration_ms'          => $durationMs,
            ]);
        });
    }

    /**
     * Mark an instance as cancelled.
     */
    public function cancel(WorkflowInstance $instance, string $reason = 'manual'): void
    {
        $this->transition(
            instance: $instance,
            toStep:   $instance->current_step ?? 'cancelled',
            outcome:  'cancelled',
            payload:  ['reason' => $reason],
        );
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /**
     * Map a step outcome to a workflow instance status.
     */
    private function deriveStatus(string $outcome): string
    {
        return match ($outcome) {
            'started'   => 'running',
            'completed' => 'running',  // instance stays running until all steps are done
            'waiting'   => 'waiting',
            'failed'    => 'failed',
            'cancelled' => 'cancelled',
            'skipped'   => 'running',
            'guarded'   => 'failed',
            default     => 'running',
        };
    }

    private function resolveActor(): string
    {
        $user = $this->auth->user();

        return $user ? (string) $user->getAuthIdentifier() : 'system';
    }
}
