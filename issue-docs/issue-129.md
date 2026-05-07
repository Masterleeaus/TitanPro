# Issue 129 — Missing `getEloquentQuery()` org scoping on 8 Filament resources

## Summary

Eight Filament admin resources had no `getEloquentQuery()` override, causing Filament to use
the base model query and return records from **all** organisations to any authenticated admin.
This fix adds tenant-scoped query overrides to each affected resource.

## Files Changed

| File | Fix Applied |
|------|-------------|
| `app/Filament/Resources/InvoiceResource.php` | Added `getEloquentQuery()` scoped by `organization_id` |
| `app/Filament/Resources/PaymentResource.php` | Added `getEloquentQuery()` scoped by `organization_id` |
| `app/Filament/Resources/EstimateResource.php` | Added `getEloquentQuery()` scoped by `organization_id` |
| `app/Filament/Resources/AttachmentResource.php` | Added `getEloquentQuery()` scoped by `organization_id` |
| `app/Filament/Resources/OrganizationSettingResource.php` | Added `getEloquentQuery()` scoped by `organization_id` |
| `app/Filament/Resources/JobMessageResource.php` | Added `getEloquentQuery()` scoped via `job.organization_id` (`whereHas`) |
| `app/Filament/Resources/EstimatePackageResource.php` | Added `getEloquentQuery()` scoped via `estimate.organization_id` (`whereHas`) |
| `app/Filament/Resources/DriverLocationResource.php` | Already had `getEloquentQuery()` — no change needed |

## Fixes Applied

### Resources with a direct `organization_id` column

The following resources (`Invoice`, `Payment`, `Estimate`, `Attachment`, `OrganizationSetting`)
all have a first-class `organization_id` column on their underlying table. The same null-safe
pattern used by `DriverLocationResource` is applied: if no org context is available the query
returns an empty result set (`whereRaw('1 = 0')`) rather than accidentally exposing orphaned
records.

```php
public static function getEloquentQuery(): Builder
{
    $organizationId = auth()->user()?->organization_id;

    if ($organizationId === null) {
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    return parent::getEloquentQuery()
        ->where('organization_id', $organizationId);
}
```

### Resources without a direct `organization_id` column

`JobMessage` and `EstimatePackage` do not carry `organization_id` themselves; they are scoped
via their parent relationship using the same null-safe guard:

```php
// JobMessageResource — scope through the job relationship
public static function getEloquentQuery(): Builder
{
    $organizationId = auth()->user()?->organization_id;

    if ($organizationId === null) {
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    return parent::getEloquentQuery()
        ->whereHas('job', fn (Builder $q) =>
            $q->where('organization_id', $organizationId));
}

// EstimatePackageResource — scope through the estimate relationship
public static function getEloquentQuery(): Builder
{
    $organizationId = auth()->user()?->organization_id;

    if ($organizationId === null) {
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    return parent::getEloquentQuery()
        ->whereHas('estimate', fn (Builder $q) =>
            $q->where('organization_id', $organizationId));
}
```

## Next Steps

- Consider adding a global `TenantScope` to the `JobMessage` and `EstimatePackage` models so
  that all queries (not just Filament resource queries) are automatically tenant-scoped.
- Review any additional Filament resources or custom query builders that may bypass
  `getEloquentQuery()` (e.g. relation managers, custom list pages).
- Add feature tests that assert cross-org records are invisible to admin users authenticated
  as org B when records belong to org A.
