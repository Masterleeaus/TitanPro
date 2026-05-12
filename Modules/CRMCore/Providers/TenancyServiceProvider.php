<?php

namespace Modules\CRMCore\Providers;

use Illuminate\Support\ServiceProvider;

class TenancyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/tenancy.php', 'crmcore.tenancy');
    }

    public function boot(): void
    {
        //
    }
}
