<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins;

use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\ShareController;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Contracts\AbstractPlugin;
use Illuminate\Routing\Router;

/**
 * SharePlugin
 *
 * Shareable read-only chat links.
 * Public share view requires no authentication.
 */
class SharePlugin extends AbstractPlugin
{
    public function id(): string    { return 'tzc-share'; }
    public function label(): string { return 'Chat Sharing'; }

    public function routes(Router $router): void
    {
        // Authenticated — create share link
        $router->middleware(['web', 'auth'])
            ->post('dashboard/user/chat/share/create', [ShareController::class, 'createLink'])
            ->name('user.tzc.share.create');

        // Public — view shared chat (no auth)
        $router->middleware('web')
            ->get('share/{category}/{chat}/{message}', [ShareController::class, 'share'])
            ->name('tzc.share.view');
    }
}
