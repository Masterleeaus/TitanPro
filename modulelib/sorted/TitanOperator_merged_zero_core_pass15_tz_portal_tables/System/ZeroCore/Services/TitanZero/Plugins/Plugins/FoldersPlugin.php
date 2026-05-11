<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins;

use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\AIChatProFoldersController;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Contracts\AbstractPlugin;
use Illuminate\Routing\Router;

/**
 * FoldersPlugin
 *
 * Chat folder organisation — CRUD, Alpine infinite-scroll sidebar,
 * chat move/rename/pin. Always enabled (core UX feature).
 */
class FoldersPlugin extends AbstractPlugin
{
    public function id(): string    { return 'tzc-folders'; }
    public function label(): string { return 'Chat Folders'; }

    public function routes(Router $router): void
    {
        $router->middleware(['web', 'auth'])
            ->prefix('dashboard/user/chat/folders')
            ->name('user.tzc.folders.')
            ->group(function () use ($router) {
                $router->get('/',            [AIChatProFoldersController::class, 'getFolders'])->name('index');
                $router->post('/',           [AIChatProFoldersController::class, 'store'])->name('store');
                $router->put('{id}',         [AIChatProFoldersController::class, 'update'])->name('update');
                $router->delete('{id}',      [AIChatProFoldersController::class, 'destroy'])->name('destroy');
                $router->get('{id}/chats',   [AIChatProFoldersController::class, 'getChats'])->name('chats');
                $router->post('move/{chatId}', [AIChatProFoldersController::class, 'moveChat'])->name('move');
            });
    }
}
