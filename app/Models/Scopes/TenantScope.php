<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * TenantScope
 *
 * Global Eloquent scope that restricts every query on a TenantAware model
 * to the current authenticated user's organisation.
 *
 * The scope is a no-op when:
 *  - No user is authenticated (queue/CLI contexts, unauthenticated routes).
 *  - The model is being accessed in a `withoutTenantScope()` call.
 *
 * This mirrors the pattern used by Laravel's SoftDeletingScope: it can be
 * removed for a single query with `->withoutGlobalScope(TenantScope::class)`.
 */
class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return;
        }

        $builder->where($model->qualifyColumn('organization_id'), $organizationId);
    }
}
