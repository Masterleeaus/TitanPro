<?php

namespace Modules\GroundZeroOps\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\GroundZeroOps\Models\Dispatch;
use Modules\GroundZeroOps\Models\GroundZeroJob;
use Modules\GroundZeroOps\Models\Incident;
use Modules\GroundZeroOps\Models\Shift;
use Modules\GroundZeroOps\Policies\DispatchPolicy;
use Modules\GroundZeroOps\Policies\GroundZeroJobPolicy;
use Modules\GroundZeroOps\Policies\IncidentPolicy;
use Modules\GroundZeroOps\Policies\ShiftPolicy;
use Modules\GroundZeroOps\Services\DispatchService;
use Modules\GroundZeroOps\Services\ShiftService;

class GroundZeroOpsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'groundzeroops');
        $this->mergeConfigFrom(__DIR__ . '/../Config/features.php', 'groundzeroops.features');
        $this->mergeConfigFrom(__DIR__ . '/../Config/permissions.php', 'groundzeroops.permissions');
        $this->mergeConfigFrom(__DIR__ . '/../Config/ai.php', 'groundzeroops.ai');

        $this->app->singleton(DispatchService::class);
        $this->app->singleton(ShiftService::class);

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(FilamentServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'groundzeroops');

        Gate::policy(GroundZeroJob::class, GroundZeroJobPolicy::class);
        Gate::policy(Shift::class, ShiftPolicy::class);
        Gate::policy(Incident::class, IncidentPolicy::class);
        Gate::policy(Dispatch::class, DispatchPolicy::class);
    }
}
