# Issue 197 — Add `organization_id` column + TenantScope to `JobMessage`, `EstimatePackage`, `JobTypeChecklistItem`

## Issue Summary

Three models (`JobMessage`, `EstimatePackage`, `JobTypeChecklistItem`) relied on parent-relationship lookups for tenant scoping. This imposed a `whereHas` query cost on every list page and left any code path that bypasses the policy/resource layer completely unscoped by default. This follow-up adds first-class `organization_id` support to all three models, mirroring the pattern applied to `driver_locations` in issue #191.

## Root Cause

The tables for `job_messages`, `estimate_packages`, and `job_type_checklist_items` had no `organization_id` column. Tenant isolation depended on joining to the parent table (`field_jobs`, `estimates`, `job_types`) via `whereHas` in Filament resources and navigating the parent relationship in policies. This was both expensive and fragile.

## Files Changed

| File | Change |
|------|--------|
| `database/migrations/2026_05_11_000001_add_organization_id_to_job_messages_table.php` | Add nullable `organization_id`, index, FK to `organizations`, backfill from `field_jobs.organization_id` |
| `database/migrations/2026_05_11_000002_add_organization_id_to_estimate_packages_table.php` | Add nullable `organization_id`, index, FK to `organizations`, backfill from `estimates.organization_id` |
| `database/migrations/2026_05_11_000003_add_organization_id_to_job_type_checklist_items_table.php` | Add nullable `organization_id`, index, FK to `organizations`, backfill from `job_types.organization_id` |
| `app/Models/JobMessage.php` | Implement `TenantAware`, use `BelongsToTenant`, add `organization_id` to fillable, add `booted()` to infer on create, add `organization()` relation, add `HasFactory` |
| `app/Models/EstimatePackage.php` | Implement `TenantAware`, use `BelongsToTenant`, add `organization_id` to fillable, add `booted()` to infer on create, add `organization()` relation |
| `app/Models/JobTypeChecklistItem.php` | Implement `TenantAware`, use `BelongsToTenant`, add `organization_id` to fillable, add `booted()` to infer on create, add `organization()` relation |
| `app/Filament/Resources/JobMessageResource.php` | Simplified `getEloquentQuery()` from `whereHas('job', ...)` to direct `where('organization_id', ...)` |
| `app/Filament/Resources/EstimatePackageResource.php` | Simplified `getEloquentQuery()` from `whereHas('estimate', ...)` to direct `where('organization_id', ...)` |
| `app/Filament/Resources/JobTypeChecklistItemResource.php` | Simplified `getEloquentQuery()` from `whereHas('jobType', ...)` to direct `where('organization_id', ...)` |
| `app/Policies/JobMessagePolicy.php` | Updated ownership checks from `$model->job?->organization_id` to direct `$model->organization_id` |
| `app/Policies/EstimatePackagePolicy.php` | Updated ownership checks from `$model->estimate?->organization_id` to direct `$model->organization_id` |
| `app/Policies/JobTypeChecklistItemPolicy.php` | Updated ownership checks from `$model->jobType?->organization_id` to direct `$model->organization_id` |
| `database/factories/JobMessageFactory.php` | New factory for `JobMessage` (was missing) |
| `database/factories/EstimatePackageFactory.php` | New factory for `EstimatePackage` (was missing) |
| `database/factories/JobTypeChecklistItemFactory.php` | Added `organization_id` to factory definition |
| `tests/Feature/TenantIsolationTest.php` | Extended `TenantAware models have TenantScope registered` dataset; added cross-org isolation and auto-org-id tests for all three models |

## Fixes Applied

### Migrations

Each migration adds `organization_id` as a nullable `unsignedBigInteger` with an index and a foreign key to `organizations.id` (nullOnDelete). It then backfills existing rows using a correlated subquery against the parent table.

### Models

Each model now:
- Implements `App\Contracts\TenantAware`
- Uses `App\Models\Concerns\BelongsToTenant` (which registers `TenantScope` as a global scope)
- Includes `organization_id` in `$fillable`
- Infers `organization_id` in `booted()::creating` from the authenticated user's `organization_id`, falling back to a direct lookup on the parent model using `withoutGlobalScopes()` for queue/CLI safety
- Exposes an `organization()` `BelongsTo` relation

### Filament Resources

`getEloquentQuery()` in all three resources now uses direct `where('organization_id', $organizationId)` instead of `whereHas` on the parent relationship, eliminating the JOIN cost on every list page.

### Policies

Ownership checks in view/update/delete/restore/replicate/forceDelete methods now compare `$model->organization_id` directly instead of traversing `$model->job?->organization_id`, `$model->estimate?->organization_id`, or `$model->jobType?->organization_id`. This removes the N+1 relation load in policy gate calls.

## Next Steps

- Consider adding a `tenancy:check` artisan command assertion for the three new TenantAware models.
- Review any API endpoints that create `JobMessage` records outside the web request context (e.g., notification jobs/queues) and ensure `organization_id` is passed explicitly when no user is authenticated.
