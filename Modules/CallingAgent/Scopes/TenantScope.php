<?php

namespace Modules\CallingAgent\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Modules\CallingAgent\Support\TenantContext;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! config('calling-agent.tenancy.enabled', true)) {
            return;
        }

        $tenantId = TenantContext::id();

        if ($tenantId === null) {
            return;
        }

        $builder->where(
            $model->qualifyColumn(config('calling-agent.tenancy.tenant_column', 'tenant_id')),
            $tenantId,
        );
    }
}
