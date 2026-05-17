<?php

namespace Modules\CRMCore\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CRMCore\Services\BillingEntityDetector;

class BillingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/billing.php', 'crmcore.billing');
        $this->app->singleton(BillingEntityDetector::class);
    }

    public function boot(): void
    {
        //
    }
}
