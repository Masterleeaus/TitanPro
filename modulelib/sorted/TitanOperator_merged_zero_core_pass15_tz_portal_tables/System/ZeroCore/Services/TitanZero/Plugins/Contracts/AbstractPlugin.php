<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Contracts;

use Illuminate\Routing\Router;

/**
 * TitanZeroChat AbstractPlugin
 *
 * Provides no-op defaults for every contract method.
 * Plugin authors only override the methods they need.
 *
 * Usage:
 *   class MyPlugin extends AbstractPlugin
 *   {
 *       public function id(): string    { return 'tzc-my-feature'; }
 *       public function label(): string { return 'My Feature'; }
 *       public function routes(Router $router): void { ... }
 *   }
 */
abstract class AbstractPlugin implements PluginInterface
{
    /**
     * Default: enabled if no feature flag key is defined.
     * Override to gate on a settings table value.
     */
    public function enabled(): bool
    {
        return true;
    }

    public function register(): void
    {
        // no-op by default
    }

    public function routes(Router $router): void
    {
        // no-op by default
    }

    public function migrations(): ?string
    {
        return null;
    }

    public function views(): array
    {
        return [];
    }

    public function assets(): array
    {
        return [];
    }

    public function commands(): array
    {
        return [];
    }

    public function settings(): array
    {
        return [];
    }
}
