<?php

namespace Modules\TitanTalk\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Modules\TitanTalk\Http\Middleware\InjectTitanTalkMenu;

class TitanTalkServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // NOTE:
        // - Routes are registered by Modules\TitanTalk\Providers\RouteServiceProvider
        // - Views are registered by Modules\TitanTalk\Providers\ViewServiceProvider
        // This provider is responsible for module-level runtime wiring that WorkSuite
        // does not do automatically (e.g. safe sidebar injection without core edits).

        /** @var Router $router */
        $router = $this->app['router'];

        // Register + apply the menu injector middleware so the module shows in the
        // WorkSuite sidebar without modifying core views.
        $router->aliasMiddleware('titantalk.menu', InjectTitanTalkMenu::class);
        $router->pushMiddlewareToGroup('web', InjectTitanTalkMenu::class);
    }
}
