<?php

declare(strict_types=1);

namespace Modules\Dispatch\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Dispatch\Console\Commands\DispatchInstallCommand;
use Modules\Dispatch\Console\Diagnostics\DispatchHealthCheckCommand;
use Modules\Dispatch\Console\Schedulers\DispatchSlaSweep;
use Modules\Dispatch\Contracts\Services\DispatchSchedulerContract;
use Modules\Dispatch\Contracts\Services\DispatchStatusContract;
use Modules\Dispatch\Contracts\Services\DispatchTransitionGuardContract;
use Modules\Dispatch\Services\Core\DispatchScheduler;
use Modules\Dispatch\Services\Core\DispatchStatusService;
use Modules\Dispatch\Services\Core\DispatchTransitionGuard;
use Modules\Dispatch\Services\Allocation\TechnicianAvailabilityService;
use Modules\Dispatch\Services\Allocation\TechnicianMatchingService;
use Modules\Dispatch\Services\Routing\RouteOptimisationService;
use Modules\Dispatch\Services\Routing\TravelTimeService;
use Modules\Dispatch\Support\Validators\DispatchScheduleValidator;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'dispatch');
        $this->mergeConfigFrom(__DIR__.'/../Config/workflows.php', 'dispatch.workflows');

        $this->app->singleton(TechnicianAvailabilityService::class);
        $this->app->singleton(TechnicianMatchingService::class);
        $this->app->singleton(TravelTimeService::class);
        $this->app->singleton(RouteOptimisationService::class);
        $this->app->singleton(DispatchSchedulerContract::class, DispatchScheduler::class);
        $this->app->singleton(DispatchStatusContract::class, DispatchStatusService::class);
        $this->app->singleton(DispatchTransitionGuardContract::class, DispatchTransitionGuard::class);
        $this->app->singleton(DispatchScheduleValidator::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([DispatchInstallCommand::class, DispatchHealthCheckCommand::class, DispatchSlaSweep::class]);
        }

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'dispatch');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'dispatch');
    }
}
