<?php

namespace Modules\CleaningJobs\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Modules\CleaningJobs\Tenancy\Resolvers\CompanyTenantResolver;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        app(CompanyTenantResolver::class)->applyToBuilder($builder);
    }
}
