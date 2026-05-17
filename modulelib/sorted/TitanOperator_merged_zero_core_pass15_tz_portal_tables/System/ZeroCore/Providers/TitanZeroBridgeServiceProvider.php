<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\ZeroCore\Providers;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Assistants\TitanZeroAssistant;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Consensus\TriCoreConsensus;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Context\SignalEnvelopeBuilder;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Engines\TitanZeroEngine;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins\CanvasPlugin;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins\ChatSettingPlugin;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins\CoreChatPlugin;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins\FileChatPlugin;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins\FoldersPlugin;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins\MemoryPlugin;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins\SharePlugin;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins\TempChatPlugin;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins\WebchatPlugin;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Registry\PluginRegistry;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Registry\TitanToolRegistry;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Systems\TitanZeroSystem;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support\ZeroNavigation;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class TitanZeroBridgeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SignalEnvelopeBuilder::class);
        $this->app->singleton(TriCoreConsensus::class);
        $this->app->singleton(TitanToolRegistry::class);
        $this->app->singleton(TitanZeroSystem::class);
        $this->app->singleton(TitanZeroEngine::class);
        $this->app->singleton(TitanZeroAssistant::class);
        $this->app->singleton(PluginRegistry::class, fn ($app) => new PluginRegistry($app));

        $registry = $this->app->make(PluginRegistry::class);
        $registry->registerMany([
            new CoreChatPlugin(),
            new FoldersPlugin(),
            new MemoryPlugin(),
            new FileChatPlugin(),
            new CanvasPlugin(),
            new TempChatPlugin(),
            new ChatSettingPlugin(),
            new SharePlugin(),
            new WebchatPlugin(),
        ]);
        $registry->discoverFromConfig();
        $registry->bootRegister();
    }

    public function boot(): void
    {
        View::addLocation(__DIR__ . '/../../../resources/views');

        view()->composer('titan_operator::default.panel.user.titanzero.*', function ($view): void {
            $view->with('zeroNavigation', ZeroNavigation::items());
        });

        $this->loadTranslationsFrom(resource_path('lang/vendor/titanzero-chat'), 'titanzero-chat');
        $this->loadRoutesFrom(__DIR__ . '/../routes/titan_zero_routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../../database/migrations/titan_zero');

        $registry = $this->app->make(PluginRegistry::class);
        $registry->bootMigrations();
        $registry->bootViews();
        $registry->bootRoutes($this->app->make(Router::class));
        $registry->seedSettings();

        if ($this->app->runningInConsole()) {
            $this->commands($registry->collectCommands());
        }
    }
}
