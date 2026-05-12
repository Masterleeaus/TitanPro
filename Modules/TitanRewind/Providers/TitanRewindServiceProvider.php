<?php

namespace Modules\TitanRewind\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\TitanRewind\Observers\TitanRewindObserver;
use Modules\TitanRewind\Services\RewindAuditService;
use Modules\TitanRewind\Services\RewindCaseService;
use Modules\TitanRewind\Services\RewindFixService;
use Modules\TitanRewind\Services\RewindSuggestionService;
use Modules\TitanRewind\Services\TitanPulseBridge;

class TitanRewindServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'titan-rewind');

        $this->app->singleton(RewindCaseService::class);
        $this->app->singleton(TitanPulseBridge::class);
        $this->app->singleton(RewindSuggestionService::class);
        $this->app->singleton(RewindAuditService::class);
        $this->app->singleton(RewindFixService::class);
        $this->app->singleton(TitanRewindObserver::class);
    }

    public function boot(): void
    {
        $migrationsPath = module_path('TitanRewind', 'Database/Migrations');
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }

        Event::listen([
            'eloquent.created: *',
            'eloquent.updated: *',
            'eloquent.deleted: *',
        ], function (string $eventName, array $data): void {
            $model = $data[0] ?? null;

            if (! $model instanceof Model) {
                return;
            }

            $this->app->make(TitanRewindObserver::class)->record($eventName, $model);
        });
    }
}
