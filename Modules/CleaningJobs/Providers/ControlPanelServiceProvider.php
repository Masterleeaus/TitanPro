<?php

namespace Modules\CleaningJobs\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CleaningJobs\ControlPanel\Metrics\DashboardMetrics;
use Modules\CleaningJobs\ControlPanel\Tables\Providers\JobsTableProvider;
use Modules\CleaningJobs\ControlPanel\Tables\Providers\RequestsTableProvider;
use Modules\CleaningJobs\ControlPanel\Tables\TabsRegistry;
use Modules\CleaningJobs\ControlPanel\Widgets\OperationalWidgets;

class ControlPanelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DashboardMetrics::class);
        $this->app->singleton(OperationalWidgets::class);
        $this->app->singleton(JobsTableProvider::class);
        $this->app->singleton(RequestsTableProvider::class);
        $this->app->singleton(TabsRegistry::class);
    }
}
