<?php

namespace Modules\CleaningJobs\Traits;

use Modules\CleaningJobs\Scopes\TenantScope;
use Modules\CleaningJobs\Tenancy\Resolvers\CompanyTenantResolver;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(TenantScope::class, app(TenantScope::class));

        static::creating(function ($model): void {
            app(CompanyTenantResolver::class)->stampModel($model);
        });
    }
}
