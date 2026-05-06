<?php

namespace Modules\TitanCore\AI;

use Modules\TitanCore\AI\ValueObjects\ToolResult;
use Modules\TitanCore\Contracts\AI\ToolExecutorContract;
use Modules\TitanCore\Exceptions\AI\ToolHandlerNotFoundException;
use Modules\TitanCore\Exceptions\AI\ToolInputValidationException;

/**
 * Declared tool executor.
 *
 * Resolves the handler class declared in a tool manifest, validates input
 * against the manifest schema, calls the handler, and wraps the result in
 * a consistent {@see ToolResult} value object.
 *
 * The manifest registry is a plain array of tool definitions keyed by tool
 * name.  Each definition may carry:
 *
 *   [
 *     'handler'      => 'Fully\Qualified\HandlerClass',   // required
 *     'input_schema' => [                                 // optional
 *       'field_name' => 'required|string',
 *       ...
 *     ],
 *   ]
 *
 * Handler classes must expose a public `__invoke(array $params): array` method
 * that returns a normalised result array.
 */
class ToolExecutor implements ToolExecutorContract
{
    /** @param  array<string, array>  $manifest  Tool definitions keyed by tool name. */
    public function __construct(private readonly array $manifest = []) {}

    /**
     * {@inheritdoc}
     */
    public function execute(string $toolName, array $params, array $context = []): ToolResult
    {
        $definition = $this->manifest[$toolName] ?? null;

        $handlerClass = $definition['handler'] ?? null;

        if ($handlerClass === null || !class_exists($handlerClass)) {
            throw new ToolHandlerNotFoundException($toolName, $handlerClass ?? '(not declared)');
        }

        $schema = $definition['input_schema'] ?? [];

        $this->validateInput($toolName, $params, $schema);

        $handler = new $handlerClass();
        $raw = $handler($params);

        return new ToolResult(
            ok: true,
            tool: $toolName,
            data: is_array($raw) ? $raw : ['result' => $raw],
            message: 'ok',
        );
    }

    /**
     * Validate $params against a simple required-field schema.
     *
     * Schema format: ['field' => 'required|string', ...]
     * The `required` rule treats a missing key, a null value, or an empty
     * string as an invalid input — all three forms fail validation.
     * Additional rules (type checks, min/max) can be added here without
     * breaking existing handlers.
     *
     * @param  array<string, string>  $schema
     *
     * @throws ToolInputValidationException
     */
    private function validateInput(string $toolName, array $params, array $schema): void
    {
        $errors = [];

        foreach ($schema as $field => $rules) {
            $ruleList = array_map('trim', explode('|', $rules));

            if (in_array('required', $ruleList, true)) {
                if (!array_key_exists($field, $params) || $params[$field] === null || $params[$field] === '') {
                    $errors[$field] = 'This field is required.';
                }
            }
        }

        if (!empty($errors)) {
            throw new ToolInputValidationException($toolName, $errors);
        }
    }
}
