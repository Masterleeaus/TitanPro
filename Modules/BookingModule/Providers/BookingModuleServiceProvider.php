<?php

namespace Modules\BookingModule\Providers;

use Modules\BookingModule\Console\ActivateModuleCommand;
use Modules\BookingModule\Console\DispatchBookingRemindersCommand;
use Modules\BookingModule\Console\PruneBookingReminderLogsCommand;
use Modules\BookingModule\Console\Commands\BookingModuleHealthCommand;
use Modules\BookingModule\Services\BookingModuleService;
use Modules\BookingModule\Services\Contracts\BookingModuleServiceContract;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\BookingModule\Services\BookingFSMService;
use Modules\BookingModule\Services\BookingAutoInvoiceService;
use Modules\BookingModule\Contracts\VerticalContextResolverContract;
use Modules\BookingModule\Services\VerticalPackService;
use Modules\BookingModule\Verticals\Support\VerticalContext;

class BookingModuleServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'BookingModule';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'bookingmodule';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ActivateModuleCommand::class,
                DispatchBookingRemindersCommand::class,
                PruneBookingReminderLogsCommand::class,
                BookingModuleHealthCommand::class,
            ]);
        }

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        if (class_exists(\Modules\BookingModule\Entities\Appointment::class) && class_exists(\Modules\BookingModule\Observers\AppointmentObserver::class)) { \Modules\BookingModule\Entities\Appointment::observe(\Modules\BookingModule\Observers\AppointmentObserver::class); }
        if (class_exists(\Modules\BookingModule\Entities\Schedule::class) && class_exists(\Modules\BookingModule\Observers\ScheduleObserver::class)) { \Modules\BookingModule\Entities\Schedule::observe(\Modules\BookingModule\Observers\ScheduleObserver::class); }
        if (class_exists(\Modules\BookingModule\Entities\Schedule::class) && class_exists(\Modules\BookingModule\Observers\ScheduleBookingObserver::class)) { \Modules\BookingModule\Entities\Schedule::observe(\Modules\BookingModule\Observers\ScheduleBookingObserver::class); }
        if (isset($this->app['router']) && class_exists(\Modules\BookingModule\Http\Middleware\PublicBookingHoneypot::class)) { $this->app['router']->aliasMiddleware('appointment.public.honeypot', \Modules\BookingModule\Http\Middleware\PublicBookingHoneypot::class); }
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (!class_exists(\Modules\ProviderManagement\Entities\Provider::class)) {
            class_alias(\Modules\BookingModule\Support\Compat\ProviderCompat::class, \Modules\ProviderManagement\Entities\Provider::class);
        }
        if (!class_exists(\Modules\ProviderManagement\Entities\SubscribedService::class)) {
            class_alias(\Modules\BookingModule\Support\Compat\SubscribedServiceCompat::class, \Modules\ProviderManagement\Entities\SubscribedService::class);
        }
        if (!class_exists(\Modules\ZoneManagement\Entities\Zone::class)) {
            class_alias(\Modules\BookingModule\Support\Compat\ZoneCompat::class, \Modules\ZoneManagement\Entities\Zone::class);
        }

        $helperPath = module_path($this->moduleName, 'Support/helpers.php');
        if (file_exists($helperPath)) { require_once $helperPath; }

        $this->app->register(RouteServiceProvider::class);
        if (class_exists(\Modules\BookingModule\Providers\FilamentServiceProvider::class) && class_exists(\Filament\Facades\Filament::class)) { $this->app->register(\Modules\BookingModule\Providers\FilamentServiceProvider::class); }
        if (class_exists(\Modules\BookingModule\Providers\AuthServiceProvider::class)) { $this->app->register(\Modules\BookingModule\Providers\AuthServiceProvider::class); }
        if (class_exists(\Modules\BookingModule\Providers\EventServiceProvider::class)) { $this->app->register(\Modules\BookingModule\Providers\EventServiceProvider::class); }

        // Bind FSM services as singletons.
        $this->app->singleton(BookingFSMService::class);
        $this->app->singleton(BookingAutoInvoiceService::class);
        $this->app->singleton(BookingModuleService::class);
        $this->app->bind(BookingModuleServiceContract::class, BookingModuleService::class);
        $this->app->singleton(VerticalPackService::class);
        $this->app->bind(VerticalContextResolverContract::class, function () {
            return new class implements VerticalContextResolverContract {
                public function resolve(?int $companyId = null, ?string $vertical = null): array
                {
                    $service = app(VerticalPackService::class);
                    $vertical = $vertical ?: (string) config('bookingmodule.verticals.default', 'services');
                    return (new VerticalContext($vertical, $companyId, $service->resolve($vertical)))->toArray();
                }
            };
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        foreach (['config','module','navigation','verticals','auto_assign','automation','dispatch','legacy_import','notifications','permissions'] as $cfg) {
            $cfgPath = module_path($this->moduleName, 'Config/' . $cfg . '.php');
            if (file_exists($cfgPath)) {
                $this->publishes([$cfgPath => config_path($this->moduleNameLower . '_' . $cfg . '.php')], 'config');
                $this->mergeConfigFrom($cfgPath, $this->moduleNameLower . '.' . $cfg);
            }
        }
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
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
        foreach ((array) \Config::get('view.paths', []) as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
