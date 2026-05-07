<?php

use App\Platform\Automation\HandlerExecutor;
use Illuminate\Container\Container;

// Inline stub handler used across tests.
class StubHandler
{
    public function handle(array $payload): array
    {
        return array_merge($payload, ['handled' => true]);
    }
}

class NoHandleMethodHandler
{
    public function execute(array $payload): void {}
}

// ─── Basic execution ─────────────────────────────────────────────────────────

test('executes the handler and returns its output', function () {
    $container = new Container;
    $executor  = new HandlerExecutor($container);

    $automation = [
        'id'      => 'test.auto',
        'handler' => StubHandler::class,
    ];

    $output = $executor->execute($automation, ['key' => 'value']);

    expect($output)->toBe(['key' => 'value', 'handled' => true]);
});

test('throws RuntimeException when no handler is configured', function () {
    $container = new Container;
    $executor  = new HandlerExecutor($container);

    $automation = ['id' => 'missing.handler'];

    expect(fn () => $executor->execute($automation))
        ->toThrow(\RuntimeException::class, 'no handler configured');
});

test('throws RuntimeException when handler class does not exist', function () {
    $container = new Container;
    $executor  = new HandlerExecutor($container);

    $automation = [
        'id'      => 'bad.handler',
        'handler' => 'Nonexistent\\HandlerClass',
    ];

    expect(fn () => $executor->execute($automation))
        ->toThrow(\RuntimeException::class, 'does not exist');
});

test('throws RuntimeException when handler has no handle method', function () {
    $container = new Container;
    $executor  = new HandlerExecutor($container);

    $automation = [
        'id'      => 'no.method',
        'handler' => NoHandleMethodHandler::class,
    ];

    expect(fn () => $executor->execute($automation))
        ->toThrow(\RuntimeException::class, 'must implement a handle() method');
});
