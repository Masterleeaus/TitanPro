<?php

namespace App\Tenancy;

use Illuminate\Http\Request;

/**
 * TenantResolver
 *
 * Identifies the current tenant's organisation_id from the request context.
 * Resolution is attempted in the following order:
 *
 *  1. Authenticated user's `organization_id` (most common: session / token auth).
 *  2. Explicit `organization_id` route parameter (e.g. /orgs/{organization_id}/…).
 *  3. `X-Tenant-ID` request header (for API clients that supply it explicitly).
 *  4. `organization_id` value stored in the current session.
 *
 * Returns null when no tenant context can be derived, allowing callers to
 * decide how to handle the absence gracefully (no scope, 403, redirect …).
 */
class TenantResolver
{
    /**
     * Resolve the organization_id for the current request.
     */
    public function resolve(Request $request): ?int
    {
        // 1. Authenticated user — the primary and most trusted source.
        //    Try both the request's user resolver and the auth guard directly so
        //    that the resolver works correctly in both HTTP and test contexts.
        $user = $request->user() ?? auth()->user();
        if ($user?->organization_id !== null) {
            return (int) $user->organization_id;
        }

        // 2. Explicit route parameter.
        $routeParam = $request->route('organization_id');
        if ($routeParam !== null) {
            return (int) $routeParam;
        }

        // 3. Dedicated request header (API / machine-to-machine usage).
        $header = $request->header('X-Tenant-ID');
        if ($header !== null) {
            return (int) $header;
        }

        // 4. Session value (e.g. set after an organisation-switch flow).
        if ($request->hasSession() && $request->session()->has('organization_id')) {
            return (int) $request->session()->get('organization_id');
        }

        return null;
    }
}
