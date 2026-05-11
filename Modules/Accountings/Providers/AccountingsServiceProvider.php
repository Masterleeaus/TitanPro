<?php

namespace Modules\Accountings\Providers;

use App\Models\Invoice;
use Illuminate\Support\ServiceProvider;
use Modules\Accountings\Observers\InvoiceAccountingObserver;
use Modules\Accountings\Services\FinancialYearService;
use Modules\TitanZero\Services\CapabilityRegistry;

class AccountingsServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'Accountings';

    /**
     * @var string
     */
    protected $moduleNameLower = 'accountings';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->registerObservers();

        // Titan Zero + Titan Go integration (capabilities registry)
        if (class_exists(CapabilityRegistry::class)) {
            CapabilityRegistry::registerModuleFromConfig('Accountings');
        }
    }

    /**
     * Register model observers.
     */
    protected function registerObservers(): void
    {
        if (class_exists(Invoice::class)) {
            Invoice::observe(
                InvoiceAccountingObserver::class
            );
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->singleton(
            FinancialYearService::class,
            FinancialYearService::class
        );
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower.'.php'),
        ], 'config');

        $this->mergeConfigFrom(module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower);
        $this->mergeConfigFrom(module_path($this->moduleName, 'Config/books.php'), 'accountings.books');
        $this->mergeConfigFrom(module_path($this->moduleName, 'Config/zeropay.php'), 'accountings.zeropay');
        $this->mergeConfigFrom(module_path($this->moduleName, 'Config/titanzero.php'), 'accountings.titanzero');
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }
}
