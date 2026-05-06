<?php

namespace App\Platform\Workflows;

use App\Jobs\WorkflowStepDeadLetterJob;
use App\Models\WorkflowInstance;

/**
 * Executes a single workflow step according to its type declaration.
 *
 * Supported step types:
 *
 *   action    — Invokes a callable class/method with the current context.
 *   condition — Evaluates a boolean condition; branches to next_true / next_false.
 *   wait      — Parks the instance in 'waiting' status until externally resumed.
 *   parallel  — Dispatches child steps concurrently (fire-and-forget jobs).
 *
 * Step definition shape (from the workflow manifest):
 *
 *   {
 *     "key":     "send_invoice",
 *     "type":    "action",
 *     "class":   "App\\Actions\\Invoices\\SendInvoiceAction",
 *     "method":  "execute",               // optional, defaults to "execute"
 *     "guards":  [ ... ],                 // evaluated by GuardEngine before execution
 *     "retries": 3,                       // retry budget for this step
 *     "next":    "notify_customer",       // step to move to after success
 *     // For "condition" type:
 *     "condition": { ... },               // ConditionEvaluator expression
 *     "next_true":  "approve_step",
 *     "next_false": "reject_step",
 *     // For "parallel" type:
 *     "steps": [ { "key": ..., "type": ... }, ... ]
 *   }
 */
class StepExecutor
{
    public function __construct(
        private readonly ConditionEvaluator $conditionEvaluator,
        private readonly GuardEngine $guardEngine,
        private readonly TransitionEngine $transitionEngine,
    ) {}

    /**
     * Execute a step against the live workflow instance.
     *
     * Returns the key of the next step to execute, or null when the workflow
     * has reached a terminal / waiting state.
     *
     * @param  array<string, mixed>  $step     Step definition from manifest.
     * @param  WorkflowInstance      $instance The live instance record.
     * @return string|null
     */
    public function execute(array $step, WorkflowInstance $instance): ?string
    {
        $key     = (string) ($step['key'] ?? 'unknown');
        $type    = (string) ($step['type'] ?? 'action');
        $guards  = (array)  ($step['guards'] ?? []);
        $context = (array)  ($instance->context ?? []);

        // ── Guard check ───────────────────────────────────────────────────────
        if (! empty($guards)) {
            $guardResult = $this->guardEngine->check($guards, $context, $instance);

            if (! $guardResult['passed']) {
                $this->transitionEngine->transition(
                    instance:  $instance,
                    toStep:    $key,
                    outcome:   'guarded',
                    stepType:  $type,
                    payload:   ['reason' => $guardResult['reason']],
                );

                return null;
            }
        }

        return match ($type) {
            'action'    => $this->executeAction($step, $instance, $context),
            'condition' => $this->executeCondition($step, $instance, $context),
            'wait'      => $this->executeWait($step, $instance),
            'parallel'  => $this->executeParallel($step, $instance, $context),
            default     => $this->executeUnknown($step, $instance),
        };
    }

    // ── Step type handlers ────────────────────────────────────────────────────

    /**
     * Execute an "action" step by calling a class method.
     *
     * @param array<string, mixed> $step
     * @param array<string, mixed> $context
     */
    private function executeAction(array $step, WorkflowInstance $instance, array $context): ?string
    {
        $key     = (string) ($step['key'] ?? 'unknown');
        $class   = (string) ($step['class'] ?? '');
        $method  = (string) ($step['method'] ?? 'execute');
        $retries = (int)    ($step['retries'] ?? 0);

        $started = microtime(true);

        $attempt = 0;
        $lastError = null;

        do {
            try {
                if (! $class || ! class_exists($class)) {
                    throw new \RuntimeException("Step action class '{$class}' not found.");
                }

                $action = app($class);
                $result = $action->{$method}($instance, $context);

                // Merge returned context updates (if the action returns an array)
                if (is_array($result)) {
                    $instance->context = array_merge($context, $result);
                    $instance->save();
                }

                $this->transitionEngine->transition(
                    instance:    $instance,
                    toStep:      $key,
                    outcome:     'completed',
                    stepType:    'action',
                    durationMs:  (int) ((microtime(true) - $started) * 1000),
                );

                return $step['next'] ?? null;
            } catch (\Throwable $e) {
                $lastError = $e;
                $attempt++;
            }
        } while ($attempt <= $retries);

        // All retries exhausted → dead-letter queue
        $this->handleFailedStep($step, $instance, $lastError);

        return null;
    }

    /**
     * Execute a "condition" step: branch based on the evaluated result.
     *
     * @param array<string, mixed> $step
     * @param array<string, mixed> $context
     */
    private function executeCondition(array $step, WorkflowInstance $instance, array $context): ?string
    {
        $key       = (string) ($step['key'] ?? 'unknown');
        $condition = (array)  ($step['condition'] ?? []);
        $result    = $this->conditionEvaluator->evaluate($condition, $context);

        $nextStep = $result
            ? ($step['next_true']  ?? null)
            : ($step['next_false'] ?? null);

        $this->transitionEngine->transition(
            instance: $instance,
            toStep:   $key,
            outcome:  'completed',
            stepType: 'condition',
            payload:  ['result' => $result, 'next' => $nextStep],
        );

        return $nextStep;
    }

    /**
     * Execute a "wait" step: park the workflow until externally resumed.
     *
     * @param array<string, mixed> $step
     */
    private function executeWait(array $step, WorkflowInstance $instance): ?string
    {
        $key = (string) ($step['key'] ?? 'unknown');

        $this->transitionEngine->transition(
            instance: $instance,
            toStep:   $key,
            outcome:  'waiting',
            stepType: 'wait',
            payload:  ['resume_token' => $step['resume_token'] ?? null],
        );

        // Return the wait step key as a sentinel so WorkflowRunner knows to stop.
        return null;
    }

    /**
     * Execute a "parallel" step: dispatch each child step as an independent job.
     *
     * @param array<string, mixed> $step
     * @param array<string, mixed> $context
     */
    private function executeParallel(array $step, WorkflowInstance $instance, array $context): ?string
    {
        $key        = (string) ($step['key'] ?? 'unknown');
        $childSteps = (array)  ($step['steps'] ?? []);

        foreach ($childSteps as $childStep) {
            dispatch(new \App\Jobs\WorkflowStepJob($childStep, $instance->id));
        }

        $this->transitionEngine->transition(
            instance: $instance,
            toStep:   $key,
            outcome:  'completed',
            stepType: 'parallel',
            payload:  ['child_count' => count($childSteps)],
        );

        return $step['next'] ?? null;
    }

    /**
     * Handle an unknown step type.
     *
     * @param array<string, mixed> $step
     */
    private function executeUnknown(array $step, WorkflowInstance $instance): ?string
    {
        $key  = (string) ($step['key']  ?? 'unknown');
        $type = (string) ($step['type'] ?? 'unknown');

        $this->transitionEngine->transition(
            instance: $instance,
            toStep:   $key,
            outcome:  'failed',
            stepType: $type,
            error:    "Unknown step type '{$type}'.",
        );

        return null;
    }

    /**
     * Record a terminal failure and push to the dead-letter queue.
     *
     * @param array<string, mixed> $step
     */
    private function handleFailedStep(array $step, WorkflowInstance $instance, \Throwable $error): void
    {
        $key = (string) ($step['key'] ?? 'unknown');

        $this->transitionEngine->transition(
            instance: $instance,
            toStep:   $key,
            outcome:  'failed',
            stepType: (string) ($step['type'] ?? 'action'),
            error:    $error->getMessage(),
        );

        dispatch(new WorkflowStepDeadLetterJob(
            instanceId: $instance->id,
            stepKey:    $key,
            error:      $error->getMessage(),
            stepDefinition: $step,
        ));
    }
}
