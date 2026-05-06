<?php

namespace App\Platform\Workflows;

/**
 * Evaluates boolean conditions declared in workflow manifests.
 *
 * A condition definition is an array with the following shape:
 *
 *   [
 *     'field'    => 'context.variable_name',  // dot-notation path into context
 *     'operator' => '==',                      // see OPERATORS constant
 *     'value'    => 'expected_value',
 *   ]
 *
 * Multiple conditions can be combined with a top-level 'logic' key:
 *   [ 'logic' => 'and', 'conditions' => [...] ]
 *   [ 'logic' => 'or',  'conditions' => [...] ]
 *
 * A missing or null context value evaluates to false for all operators except
 * '!=' and 'not_in'.
 */
class ConditionEvaluator
{
    /** Supported comparison operators. */
    private const OPERATORS = [
        '==', '!=', '>', '>=', '<', '<=',
        'in', 'not_in', 'contains', 'starts_with', 'ends_with',
        'empty', 'not_empty',
    ];

    /**
     * Evaluate a single condition definition against the provided context.
     *
     * @param array<string, mixed> $condition
     * @param array<string, mixed> $context
     */
    public function evaluate(array $condition, array $context): bool
    {
        // Compound condition with logic combinator
        if (isset($condition['logic'], $condition['conditions'])) {
            return $this->evaluateCompound(
                (string) $condition['logic'],
                (array) $condition['conditions'],
                $context,
            );
        }

        return $this->evaluateSingle($condition, $context);
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /** @param array<string, mixed>[] $conditions */
    private function evaluateCompound(string $logic, array $conditions, array $context): bool
    {
        if ($logic === 'or') {
            foreach ($conditions as $cond) {
                if ($this->evaluate($cond, $context)) {
                    return true;
                }
            }

            return false;
        }

        // Default to "and"
        foreach ($conditions as $cond) {
            if (! $this->evaluate($cond, $context)) {
                return false;
            }
        }

        return true;
    }

    /** @param array<string, mixed> $condition */
    private function evaluateSingle(array $condition, array $context): bool
    {
        $field    = (string) ($condition['field'] ?? '');
        $operator = (string) ($condition['operator'] ?? '==');
        $expected = $condition['value'] ?? null;

        $actual = $this->resolveField($field, $context);

        return match ($operator) {
            '=='          => $actual == $expected,
            '!='          => $actual != $expected,
            '>'           => is_numeric($actual) && is_numeric($expected) && $actual > $expected,
            '>='          => is_numeric($actual) && is_numeric($expected) && $actual >= $expected,
            '<'           => is_numeric($actual) && is_numeric($expected) && $actual < $expected,
            '<='          => is_numeric($actual) && is_numeric($expected) && $actual <= $expected,
            'in'          => is_array($expected) && in_array($actual, $expected, true),
            'not_in'      => ! is_array($expected) || ! in_array($actual, $expected, true),
            'contains'    => is_string($actual) && is_string($expected) && str_contains($actual, $expected),
            'starts_with' => is_string($actual) && is_string($expected) && str_starts_with($actual, $expected),
            'ends_with'   => is_string($actual) && is_string($expected) && str_ends_with($actual, $expected),
            'empty'       => empty($actual),
            'not_empty'   => ! empty($actual),
            default       => false,
        };
    }

    /**
     * Resolve a dot-notation field path from the context array.
     *
     * Supports a 'context.' prefix for clarity in manifests.
     *
     * @param array<string, mixed> $context
     */
    private function resolveField(string $field, array $context): mixed
    {
        // Strip optional 'context.' prefix
        $path = str_starts_with($field, 'context.')
            ? substr($field, 8)
            : $field;

        $keys  = explode('.', $path);
        $value = $context;

        foreach ($keys as $key) {
            if (! is_array($value) || ! array_key_exists($key, $value)) {
                return null;
            }
            $value = $value[$key];
        }

        return $value;
    }
}
