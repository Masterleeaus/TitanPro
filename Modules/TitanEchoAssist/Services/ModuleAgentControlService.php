<?php

namespace Modules\TitanChatbot\Services;

use InvalidArgumentException;

class ModuleAgentControlService
{
    public function __construct(private readonly ModuleAgentBindingService $bindings) {}

    /** @return array<int, array<string, mixed>> */
    public function tools(): array
    {
        return $this->bindings->tools();
    }

    /** @param array<string, mixed> $payload */
    public function invoke(string $tool, array $payload = []): mixed
    {
        $actionClass = $this->bindings->resolveAction($tool);
        if ($actionClass === null) {
            throw new InvalidArgumentException("Tool [{$tool}] is not mapped to a resolvable action.");
        }

        $action = function_exists('app') ? app($actionClass) : new $actionClass();

        if (method_exists($action, 'handle')) {
            return $action->handle($payload);
        }

        if (is_callable($action)) {
            return $action($payload);
        }

        throw new InvalidArgumentException("Action [{$actionClass}] is not invokable.");
    }
}
