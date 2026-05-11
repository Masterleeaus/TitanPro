<?php

namespace Modules\CleaningJobs\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CleaningJobs\Scopes\TenantScope;
use Modules\CleaningJobs\Tenancy\Policies\TenantJobPolicy;
use Modules\CleaningJobs\Tenancy\Resolvers\CompanyTenantResolver;

class TenancyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CompanyTenantResolver::class);
        $this->app->singleton(TenantScope::class);
        $this->app->singleton(TenantJobPolicy::class);
    }
}
