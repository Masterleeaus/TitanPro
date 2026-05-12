<?php

namespace Modules\CRMCore\Providers;

use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/search.php', 'crmcore.search');
    }

    public function boot(): void
    {
        //
    }
}
