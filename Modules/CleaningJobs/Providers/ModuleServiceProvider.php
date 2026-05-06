<?php

namespace Modules\CleaningJobs\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CleaningJobs\Providers\ControlPanelServiceProvider;
use Modules\CleaningJobs\Providers\AgentServiceProvider;
use Modules\CleaningJobs\Providers\AutomationServiceProvider;
use Modules\CleaningJobs\Providers\KnowledgeServiceProvider;
use Modules\CleaningJobs\Providers\ManifestServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Support both namespaces during the CleaningJobs -> TitanWork transition.
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'cleaningjobs');
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'titanwork');

        foreach (['features','permissions','navigation','routes','ui','tenancy','billing','search','ai'] as $config) {
            $path = __DIR__ . "/../Config/{$config}.php";
            if (file_exists($path)) {
                $this->mergeConfigFrom($path, "cleaningjobs.{$config}");
                $this->mergeConfigFrom($path, "titanwork.{$config}");
            }
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\CleaningJobs\Console\Commands\WorkOrdersImportCsvCommand::class,
                \Modules\CleaningJobs\Console\Commands\WorkOrdersExportCsvCommand::class,
                \Modules\CleaningJobs\Console\Commands\WorkOrdersUninstallCommand::class,
                \Modules\CleaningJobs\Console\Commands\SendCleaningJobRemindersCommand::class,
                \Modules\CleaningJobs\Console\Commands\SyncJobFinancialsCommand::class,
            ]);
        }

        $this->app->register(ControlPanelServiceProvider::class);
        $this->app->register(AgentServiceProvider::class);
        $this->app->register(AutomationServiceProvider::class);
        $this->app->register(KnowledgeServiceProvider::class);
        $this->app->register(ManifestServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'cleaningjobs');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'titanwork');
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'cleaningjobs');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'titanwork');

        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('cleaningjobs.php'),
        ], 'cleaningjobs-config');

        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('titanwork.php'),
        ], 'titanwork-config');
    }
}
