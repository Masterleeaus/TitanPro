<?php

namespace App\Tenancy;

use Illuminate\Database\Eloquent\Model;

/**
 * CurrentTenant
 *
 * A lightweight singleton that exposes the organisation_id of the currently
 * active tenant.  Bound in `TitanModuleServiceProvider` so it is available
 * throughout the container.
 *
 * The class deliberately reads from `auth()` on every call rather than
 * caching the value at construction time.  This keeps it safe for:
 *  - Standard PHP-FPM request/response cycles.
 *  - Queue workers (where auth() returns null, giving callers a null id).
 *  - Artisan commands (same as queue workers).
 *
 * Example usage:
 *
 *   $tenantId = app(CurrentTenant::class)->id();          // ?int
 *   $resolved = app(CurrentTenant::class)->isResolved();  // bool
 *
 * To bypass the tenant scope for a single Eloquent query:
 *
 *   Customer::withoutGlobalScope(TenantScope::class)->get();
 */
class CurrentTenant
{
    /**
     * Return the organisation_id of the currently authenticated tenant, or
     * null when no user is authenticated.
     */
    public function id(): ?int
    {
        $orgId = auth()->user()?->organization_id;

        return $orgId !== null ? (int) $orgId : null;
    }

    /**
     * True when a tenant can be resolved from the current request context.
     */
    public function isResolved(): bool
    {
        return $this->id() !== null;
    }

    /**
     * Assert that a given Eloquent model record belongs to the current tenant.
     * Throws a 403 HTTP exception if ownership cannot be confirmed.
     */
    public function assertOwns(Model $model, string $column = 'organization_id'): void
    {
        abort_unless(
            $model->{$column} === $this->id(),
            403,
            'Resource does not belong to the current tenant.'
        );
    }
}
