<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins;

use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\AIChatProMemoryController;
use App\Extensions\TitanOperator\System\ZeroCore\Console\Commands\TitanZero\CleanupGuestInstructions;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Contracts\AbstractPlugin;
use Illuminate\Routing\Router;

/**
 * MemoryPlugin
 *
 * Per-user and per-guest persistent chat instructions.
 * Includes 90-day guest cleanup artisan command.
 */
class MemoryPlugin extends AbstractPlugin
{
    public function id(): string    { return 'tzc-memory'; }
    public function label(): string { return 'Chat Memory'; }

    public function routes(Router $router): void
    {
        $router->middleware(['web', 'auth'])
            ->prefix('dashboard/user/chat/memory')
            ->name('user.tzc.memory.')
            ->group(function () use ($router) {
                $router->get('instructions',    [AIChatProMemoryController::class, 'getInstructions'])->name('get');
                $router->post('instructions',   [AIChatProMemoryController::class, 'saveInstructions'])->name('save');
                $router->delete('instructions', [AIChatProMemoryController::class, 'clearInstructions'])->name('clear');
            });
    }

    public function commands(): array
    {
        return [CleanupGuestInstructions::class];
    }

    public function settings(): array
    {
        return [];
    }
}
