# Issue 197 — Audit Filament relation managers and custom list pages for tenant-scoping bypass

## Summary

Follow-up to issue 129. All Filament resources, relation managers, and custom list pages were
audited for tenant-scoping correctness. Five resources were missing the null-safe guard pattern
(`$organizationId === null → whereRaw('1 = 0')`) that prevents potential data leakage when
the authenticated user has no `organization_id` (e.g. a super-admin or an unauthenticated
queue context). Three new HTTP-level 404 tests were added covering the fixed resources.

## Audit Results

### Relation managers

`app/Filament/Resources/*/RelationManagers/` — **no relation manager files exist in this
codebase**. The directory does not exist. This audit criterion is vacuously satisfied.

### Custom list pages overriding `getTableQuery()`

Checked all files under `app/Filament/Resources/*/Pages/`, `app/Filament/TitanSolo/Resources/*/Pages/`,
`app/Filament/ZeroPay/Resources/*/Pages/`, and `app/Filament/ZeroFuss/Resources/*/Pages/`.
**No list page overrides `getTableQuery()`**. This criterion is also vacuously satisfied.

### Resource-level `getEloquentQuery()` audit

| Resource | Has `getEloquentQuery()` | Null-safe guard | Status |
|---|---|---|---|
| `CustomerResource` | ✅ | ✅ | OK |
| `PropertyResource` | ✅ | ✅ | OK |
| `JobResource` | ✅ | ✅ | OK |
| `DriverLocationResource` | ✅ | ✅ | OK |
| `InvoiceResource` | ✅ | ✅ | OK |
| `PaymentResource` | ✅ | ✅ | OK |
| `EstimateResource` | ✅ | ✅ | OK |
| `AttachmentResource` | ✅ | ✅ | OK |
| `OrganizationSettingResource` | ✅ | ✅ | OK |
| `EstimatePackageResource` | ✅ | ✅ | OK |
| `JobMessageResource` | ✅ | ✅ | OK |
| `ItemResource` | ✅ | ❌ → ✅ fixed | **Fixed** |
| `JobTypeResource` | ✅ | ❌ → ✅ fixed | **Fixed** |
| `MessageTemplateResource` | ✅ | ❌ → ✅ fixed | **Fixed** |
| `JobChecklistItemResource` | ✅ | ❌ → ✅ fixed | **Fixed** |
| `JobTypeChecklistItemResource` | ✅ | ❌ → ✅ fixed | **Fixed** |
| `CmsPageResource` | ❌ (not needed) | N/A | OK — `CmsPage` has no `organization_id`; it is a global system resource |
| `TitanSolo/CustomerResource` | ✅ | ✅ | OK |
| `TitanSolo/JobResource` | ✅ | ✅ | OK |
| `TitanSolo/InvoiceResource` | ✅ | ✅ | OK |
| `ZeroPay/InvoiceResource` | ✅ | ✅ | OK |
| `ZeroPay/PaymentResource` | ✅ | ✅ | OK |
| `ZeroFuss/BookingResource` | ✅ | ✅ | OK |

## Files Changed

| File | Fix Applied |
|------|-------------|
| `app/Filament/Resources/ItemResource.php` | Added null-safe guard to `getEloquentQuery()` |
| `app/Filament/Resources/JobTypeResource.php` | Added null-safe guard to `getEloquentQuery()` |
| `app/Filament/Resources/MessageTemplateResource.php` | Added null-safe guard to `getEloquentQuery()` |
| `app/Filament/Resources/JobChecklistItemResource.php` | Added null-safe guard; extracted `$organizationId` variable to avoid double call to `auth()->user()?->organization_id` inside the closure |
| `app/Filament/Resources/JobTypeChecklistItemResource.php` | Added null-safe guard to `getEloquentQuery()` |
| `tests/Feature/Admin/OrgScopingTest.php` | Added 3 new 404-level cross-org tests for `job-type-checklist-items`, `job-checklist-items`, and `message-templates` |

## Fixes Applied

All five resources now follow the established null-safe guard pattern from issue 129:

```php
public static function getEloquentQuery(): Builder
{
    $organizationId = auth()->user()?->organization_id;

    if ($organizationId === null) {
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    return parent::getEloquentQuery()->where('organization_id', $organizationId);
}
```

`JobChecklistItemResource` retains its intentional `OR` logic (items are visible if either
their own `organization_id` or their parent job's `organization_id` matches) but now passes
the resolved `$organizationId` into the closure rather than calling `auth()->user()?->organization_id`
twice — and returns an empty set when no org context is present.

## Next Steps

- Add a `HasFactory` trait and `JobChecklistItemFactory` / `MessageTemplateFactory` to enable
  richer factory-based tests for these models.
- Consider adding `TenantAware` / `BelongsToTenant` to `JobChecklistItem` so TenantScope is
  applied automatically at the model level, reducing reliance on manual resource-level guards.
- Monitor for any new Filament resources added to the codebase — enforce the null-safe pattern
  as a code-review checklist item.
