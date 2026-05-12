<?php

namespace Modules\ZeroFussPortal\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\ZeroFussPortal\Models\LoyaltyPoint;
use Modules\ZeroFussPortal\Models\PortalFeedback;
use Modules\ZeroFussPortal\Models\Referral;
use Modules\ZeroFussPortal\Policies\PortalPolicy;
use Modules\ZeroFussPortal\Services\LoyaltyService;
use Modules\ZeroFussPortal\Services\ReferralService;

class ZeroFussPortalServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerCustomerGuard();
        $this->registerConfig();

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(FilamentServiceProvider::class);

        $this->app->singleton(LoyaltyService::class);
        $this->app->singleton(ReferralService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'zerofussportal');

        Gate::policy(LoyaltyPoint::class, PortalPolicy::class);
        Gate::policy(PortalFeedback::class, PortalPolicy::class);
        Gate::policy(Referral::class, PortalPolicy::class);
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'zerofussportal');
        $this->mergeConfigFrom(__DIR__.'/../Config/features.php', 'zerofussportal.features');
        $this->mergeConfigFrom(__DIR__.'/../Config/permissions.php', 'zerofussportal.permissions');
    }

    private function registerCustomerGuard(): void
    {
        $guards = config('auth.guards', []);
        if (! isset($guards['customer'])) {
            $guards['customer'] = [
                'driver' => 'session',
                'provider' => 'customers',
            ];
            config(['auth.guards' => $guards]);
        }

        $providers = config('auth.providers', []);
        if (! isset($providers['customers'])) {
            $providers['customers'] = [
                'driver' => 'eloquent',
                'model' => config('auth.providers.users.model', \App\Models\User::class),
            ];
            config(['auth.providers' => $providers]);
        }
    }
}
