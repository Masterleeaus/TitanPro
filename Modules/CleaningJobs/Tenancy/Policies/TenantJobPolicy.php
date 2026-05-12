<?php

namespace Modules\CleaningJobs\Tenancy\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\CleaningJobs\Tenancy\Resolvers\CompanyTenantResolver;

class TenantJobPolicy
{
    public function allows(User $user, Model $record): bool
    {
        return app(CompanyTenantResolver::class)->belongsToCurrentTenant($record, $user);
    }
}
