<?php

namespace App\Platform\Workflows;

use App\Models\WorkflowInstance;

/**
 * Evaluates guards declared on a workflow transition before it is allowed.
 *
 * A guard definition lives inside a step definition as:
 *
 *   "guards": [
 *     { "type": "condition", "condition": { ... } },
 *     { "type": "callable",  "class": "App\\Guards\\MyGuard", "method": "passes" },
 *     { "type": "field_required", "field": "context.invoice_id" }
 *   ]
 *
 * If any guard fails, the transition is blocked and a reason is returned.
 */
class GuardEngine
{
    public function __construct(
        private readonly ConditionEvaluator $conditionEvaluator,
    ) {}

    /**
     * Check all guards for a step definition.
     *
     * @param array<string, mixed>[] $guards   Guard definitions from the manifest step.
     * @param array<string, mixed>   $context  Current workflow context.
     * @param WorkflowInstance       $instance The live workflow instance.
     *
     * @return array{passed: bool, reason: string|null}
     */
    public function check(array $guards, array $context, WorkflowInstance $instance): array
    {
        foreach ($guards as $guard) {
            $result = $this->evaluateGuard($guard, $context, $instance);

            if (! $result['passed']) {
                return $result;
            }
        }

        return ['passed' => true, 'reason' => null];
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /**
     * @param array<string, mixed> $guard
     * @param array<string, mixed> $context
     * @return array{passed: bool, reason: string|null}
     */
    private function evaluateGuard(array $guard, array $context, WorkflowInstance $instance): array
    {
        $type = (string) ($guard['type'] ?? 'condition');

        return match ($type) {
            'condition'      => $this->guardCondition($guard, $context),
            'field_required' => $this->guardFieldRequired($guard, $context),
            'callable'       => $this->guardCallable($guard, $context, $instance),
            default          => ['passed' => false, 'reason' => "Unknown guard type: {$type}"],
        };
    }

    /** @param array<string, mixed> $guard */
    private function guardCondition(array $guard, array $context): array
    {
        $condition = $guard['condition'] ?? [];

        if (empty($condition)) {
            return ['passed' => false, 'reason' => 'Guard condition definition is empty.'];
        }

        $passed = $this->conditionEvaluator->evaluate($condition, $context);

        return [
            'passed' => $passed,
            'reason' => $passed ? null : ($guard['message'] ?? 'Condition guard failed.'),
        ];
    }

    /** @param array<string, mixed> $guard */
    private function guardFieldRequired(array $guard, array $context): array
    {
        $field = (string) ($guard['field'] ?? '');

        // Strip 'context.' prefix and resolve
        $path  = str_starts_with($field, 'context.') ? substr($field, 8) : $field;
        $keys  = explode('.', $path);
        $value = $context;

        foreach ($keys as $key) {
            if (! is_array($value) || ! array_key_exists($key, $value)) {
                return [
                    'passed' => false,
                    'reason' => $guard['message'] ?? "Required field '{$field}' is missing.",
                ];
            }
            $value = $value[$key];
        }

        $passed = ! empty($value) || $value === 0 || $value === false;

        return [
            'passed' => $passed,
            'reason' => $passed ? null : ($guard['message'] ?? "Required field '{$field}' is empty."),
        ];
    }

    /**
     * Invoke a custom guard class.
     *
     * The class must have a method with signature:
     *   public function passes(WorkflowInstance $instance, array $context): bool
     *
     * @param array<string, mixed> $guard
     */
    private function guardCallable(array $guard, array $context, WorkflowInstance $instance): array
    {
        $class  = (string) ($guard['class'] ?? '');
        $method = (string) ($guard['method'] ?? 'passes');

        if (! $class || ! class_exists($class)) {
            return ['passed' => false, 'reason' => "Guard class '{$class}' not found."];
        }

        try {
            $guardInstance = app($class);
            $passed        = (bool) $guardInstance->{$method}($instance, $context);

            return [
                'passed' => $passed,
                'reason' => $passed ? null : ($guard['message'] ?? "Callable guard '{$class}::{$method}' returned false."),
            ];
        } catch (\Throwable $e) {
            return ['passed' => false, 'reason' => "Guard '{$class}::{$method}' threw: {$e->getMessage()}"];
        }
    }
}
