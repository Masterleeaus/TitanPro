<?php

namespace App\Platform\Workflows;

use App\Models\WorkflowInstance;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Log;

/**
 * Boots and drives a workflow instance through its manifest steps.
 *
 * ## Manifest shape (minimum viable)
 *
 * ```json
 * {
 *   "id":      "onboarding_flow",
 *   "version": "1.0.0",
 *   "steps": [
 *     { "key": "send_welcome", "type": "action",  "class": "...", "next": "assign_account" },
 *     { "key": "assign_account", "type": "action", "class": "...", "next": "notify_team" },
 *     { "key": "notify_team",   "type": "action",  "class": "..." }
 *   ],
 *   "retry_policy": { "max_attempts": 3 }
 * }
 * ```
 *
 * ## Usage
 *
 * ```php
 * $runner = app(WorkflowRunner::class);
 * $instance = $runner->start($manifest, context: ['user_id' => 42], companyId: 1);
 * ```
 *
 * To resume a waiting instance after an external event:
 *
 * ```php
 * $runner->resume($instance, additionalContext: ['approved_by' => 5]);
 * ```
 */
class WorkflowRunner
{
    /** Maximum steps executed in a single run to prevent infinite loops. */
    private const MAX_STEPS_PER_RUN = 100;

    public function __construct(
        private readonly StepExecutor      $stepExecutor,
        private readonly TransitionEngine  $transitionEngine,
        private readonly Guard             $auth,
    ) {}

    /**
     * Create a new workflow instance from the manifest and begin execution.
     *
     * @param  array<string, mixed>  $manifest   Parsed workflow manifest.
     * @param  array<string, mixed>  $context    Initial context/variables bag.
     * @param  int|null              $companyId  Tenant identifier.
     * @param  string|null           $entityType Polymorphic entity type (optional).
     * @param  int|null              $entityId   Polymorphic entity ID (optional).
     * @return WorkflowInstance
     */
    public function start(
        array $manifest,
        array $context = [],
        ?int $companyId = null,
        ?string $entityType = null,
        ?int $entityId = null,
    ): WorkflowInstance {
        $steps   = (array) ($manifest['steps'] ?? []);
        $firstStep = $steps[0]['key'] ?? null;

        $initiatedBy = $this->resolveActor();

        /** @var WorkflowInstance $instance */
        $instance = WorkflowInstance::create([
            'company_id'       => $companyId,
            'workflow_id'      => (string) ($manifest['id'] ?? 'unknown'),
            'workflow_version' => (string) ($manifest['version'] ?? '1.0.0'),
            'entity_type'      => $entityType,
            'entity_id'        => $entityId,
            'status'           => 'pending',
            'current_step'     => $firstStep,
            'context'          => $context,
            'attempt'          => 0,
            'initiated_by'     => $initiatedBy,
        ]);

        $this->run($instance, $manifest);

        return $instance->refresh();
    }

    /**
     * Resume a workflow instance that is currently in 'waiting' status.
     *
     * @param  array<string, mixed>  $additionalContext  Extra context to merge before resuming.
     */
    public function resume(WorkflowInstance $instance, array $additionalContext = []): WorkflowInstance
    {
        if ($instance->status !== 'waiting') {
            throw new \RuntimeException(
                "Cannot resume workflow instance #{$instance->id}: status is '{$instance->status}', expected 'waiting'."
            );
        }

        if (! empty($additionalContext)) {
            $instance->context = array_merge((array) ($instance->context ?? []), $additionalContext);
            $instance->save();
        }

        // Reload the manifest so we know the full step list
        $manifest = $this->loadManifest($instance->workflow_id);

        $this->run($instance, $manifest);

        return $instance->refresh();
    }

    // ── Private execution loop ────────────────────────────────────────────────

    /**
     * Drive the step-execution loop from the current step until a terminal
     * state is reached or the step budget is exhausted.
     *
     * @param array<string, mixed> $manifest
     */
    private function run(WorkflowInstance $instance, array $manifest): void
    {
        $stepIndex = $this->buildStepIndex((array) ($manifest['steps'] ?? []));
        $currentKey = $instance->current_step;
        $loopCount  = 0;

        while ($currentKey !== null && $loopCount < self::MAX_STEPS_PER_RUN) {
            $loopCount++;

            // Reload fresh instance state each iteration
            $instance->refresh();

            // Stop if we've hit a terminal status
            if (in_array($instance->status, ['failed', 'cancelled', 'completed'], true)) {
                return;
            }

            if (! isset($stepIndex[$currentKey])) {
                Log::warning("WorkflowRunner: step '{$currentKey}' not found in manifest '{$instance->workflow_id}'.");
                $this->transitionEngine->transition(
                    instance: $instance,
                    toStep:   $currentKey,
                    outcome:  'failed',
                    error:    "Step '{$currentKey}' not found in manifest.",
                );

                return;
            }

            $step = $stepIndex[$currentKey];

            try {
                $nextKey = $this->stepExecutor->execute($step, $instance);
            } catch (\Throwable $e) {
                Log::error("WorkflowRunner: unhandled exception in step '{$currentKey}'", [
                    'instance_id' => $instance->id,
                    'error'       => $e->getMessage(),
                ]);

                $this->transitionEngine->transition(
                    instance: $instance,
                    toStep:   $currentKey,
                    outcome:  'failed',
                    error:    $e->getMessage(),
                );

                return;
            }

            // Refresh to pick up any status changes made by StepExecutor
            $instance->refresh();

            // If the instance is now in 'waiting' or 'failed', stop the loop
            if (in_array($instance->status, ['waiting', 'failed', 'cancelled'], true)) {
                return;
            }

            $currentKey = $nextKey;
        }

        // If we've run out of steps naturally, mark the instance as completed
        if ($currentKey === null && $instance->status === 'running') {
            $instance->status       = 'completed';
            $instance->completed_at = now();
            $instance->save();
        }

        if ($loopCount >= self::MAX_STEPS_PER_RUN) {
            Log::warning("WorkflowRunner: step budget exhausted for instance #{$instance->id}.");
        }
    }

    /**
     * Build a key-indexed map of steps for O(1) lookup.
     *
     * @param  array<int, array<string, mixed>>  $steps
     * @return array<string, array<string, mixed>>
     */
    private function buildStepIndex(array $steps): array
    {
        $index = [];

        foreach ($steps as $step) {
            $key = (string) ($step['key'] ?? '');
            if ($key !== '') {
                $index[$key] = $step;
            }
        }

        return $index;
    }

    /**
     * Load a workflow manifest by its slug from the filesystem.
     *
     * Looks in app/Platform/Workflows/Definitions/{workflow_id}.json,
     * or falls back to module-level Workflows directory.
     *
     * @return array<string, mixed>
     */
    private function loadManifest(string $workflowId): array
    {
        // Primary location
        $path = app_path("Platform/Workflows/Definitions/{$workflowId}.json");

        if (file_exists($path)) {
            $contents = file_get_contents($path);
            if ($contents === false) {
                throw new \RuntimeException("Workflow manifest '{$workflowId}' could not be read from: {$path}");
            }

            return json_decode($contents, true) ?? [];
        }

        // Module fallback
        $modulesBase = base_path('Modules');
        if (is_dir($modulesBase)) {
            foreach (glob("{$modulesBase}/*/Workflows/{$workflowId}.json") as $found) {
                $contents = file_get_contents($found);
                if ($contents === false) {
                    throw new \RuntimeException("Workflow manifest '{$workflowId}' could not be read from: {$found}");
                }

                return json_decode($contents, true) ?? [];
            }
        }

        throw new \RuntimeException("Workflow manifest '{$workflowId}' not found.");
    }

    private function resolveActor(): string
    {
        $user = $this->auth->user();

        return $user ? (string) $user->getAuthIdentifier() : 'system';
    }
}
