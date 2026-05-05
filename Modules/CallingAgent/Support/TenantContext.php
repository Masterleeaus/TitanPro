<?php

namespace Modules\CallingAgent\Support;

use Illuminate\Database\Eloquent\Model;

/**
 * Tenant context helper for the CallingAgent module.
 *
 * In a multi-tenant application this class can be extended to resolve
 * the current tenant based on request context or other heuristics. The
 * default implementation returns null, allowing callers to gracefully
 * handle the absence of a tenant.
 */
class TenantContext
{
    /**
     * Resolve the current tenant model or return null if none.
     *
     * @return Model|null
     */
    public static function tenant(): ?Model
    {
        // TODO: implement tenant resolution logic
        return null;
    }
}