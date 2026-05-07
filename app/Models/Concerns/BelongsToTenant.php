<?php

namespace App\Models\Concerns;

use App\Models\Scopes\TenantScope;

/**
 * BelongsToTenant
 *
 * Mix this trait into any Eloquent model that also implements
 * `App\Contracts\TenantAware`.  The trait's boot method registers
 * TenantScope as a global scope so every query automatically filters by the
 * current authenticated user's organisation_id.
 *
 * Usage:
 *
 *   class Customer extends Model implements TenantAware
 *   {
 *       use BelongsToTenant;
 *   }
 *
 * To bypass the scope for a single query (e.g. in a super-admin context):
 *
 *   Customer::withoutGlobalScope(TenantScope::class)->all();
 */
trait BelongsToTenant
{
    /**
     * Boot the trait and register the global TenantScope.
     */
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);
    }
}
