<?php

namespace App\Platform\Automation;

use Illuminate\Contracts\Container\Container;

/**
 * HandlerExecutor resolves the handler class declared in an automation definition
 * via the service container and calls its handle() method with the payload.
 *
 * Handler classes must expose:
 *
 *   public function handle(array $payload): mixed
 *
 * The return value is forwarded back to the caller (and persisted as the run output).
 */
class HandlerExecutor
{
    public function __construct(private readonly Container $container) {}

    /**
     * Execute the handler for the given automation definition.
     *
     * @param array $automation Automation definition from AutomationRegistry
     * @param array $payload    Trigger payload
     * @return mixed            Whatever the handler returns
     *
     * @throws \RuntimeException  When no handler is configured
     * @throws \Throwable         Re-throws any exception raised by the handler
     */
    public function execute(array $automation, array $payload = []): mixed
    {
        $handlerClass = $automation['handler'] ?? null;

        if (! $handlerClass) {
            throw new \RuntimeException(
                "Automation [{$automation['id']}] has no handler configured."
            );
        }

        if (! class_exists($handlerClass)) {
            throw new \RuntimeException(
                "Automation handler class [{$handlerClass}] does not exist."
            );
        }

        $handler = $this->container->make($handlerClass);

        if (! method_exists($handler, 'handle')) {
            throw new \RuntimeException(
                "Handler [{$handlerClass}] must implement a handle() method."
            );
        }

        return $handler->handle($payload);
    }
}
