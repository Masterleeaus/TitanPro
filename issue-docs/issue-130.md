# Issue 130 — Policy classes missing organization_id ownership check

## Issue Summary

All Filament policy classes in `app/Policies/` checked permission strings (e.g. `can('View:Invoice')`) but never verified that the target record belonged to the authenticated user's organization. Any user with the correct role/permission could access records from any organization via direct URL manipulation.

## Root Cause

Policy instance methods (`view`, `update`, `delete`, `restore`, `forceDelete`, `replicate`) only called `$authUser->can('...')` without first asserting that the model's `organization_id` matched the authenticated user's `organization_id`.

## Changes Made

### Direct `organization_id` check added (model column exists)

All instance methods (`view`, `update`, `delete`, `restore`, `forceDelete`, `replicate`) in each policy now include:

```php
if ((int) $model->organization_id !== (int) $authUser->organization_id) {
    return false;
}
```

Affected files:
- `app/Policies/InvoicePolicy.php`
- `app/Policies/PaymentPolicy.php`
- `app/Policies/DriverLocationPolicy.php`
- `app/Policies/JobPolicy.php`
- `app/Policies/CustomerPolicy.php`
- `app/Policies/EstimatePolicy.php`
- `app/Policies/PropertyPolicy.php`
- `app/Policies/AttachmentPolicy.php`
- `app/Policies/ItemPolicy.php`
- `app/Policies/JobTypePolicy.php`
- `app/Policies/JobChecklistItemPolicy.php`
- `app/Policies/OrganizationSettingPolicy.php`
- `app/Policies/MessageTemplatePolicy.php`

### Indirect `organization_id` check added (check via parent relation)

Models without a direct `organization_id` column are checked through their owning parent:

- `app/Policies/EstimatePackagePolicy.php` — checks `$estimatePackage->estimate?->organization_id`
- `app/Policies/JobMessagePolicy.php` — checks `$jobMessage->job?->organization_id`
- `app/Policies/JobTypeChecklistItemPolicy.php` — checks `$jobTypeChecklistItem->jobType?->organization_id`

### Not modified (no applicable `organization_id` column)

These policies operate on external package models or platform-level models that have no per-tenant `organization_id`:

- `app/Policies/RolePolicy.php` — `Spatie\Permission\Models\Role`
- `app/Policies/CategoryPolicy.php` — `TomatoPHP\FilamentCms\Models\Category`
- `app/Policies/PostPolicy.php` — `TomatoPHP\FilamentCms\Models\Post`
- `app/Policies/MediaPolicy.php` — `Awcodes\Curator\Models\Media`
- `app/Policies/LayoutPolicy.php` — `LaraZeus\DynamicDashboard\Models\Layout`
- `app/Policies/CmsPagePolicy.php` — `App\Models\CmsPage` (platform-level, no tenant column)

## Fix Pattern

```php
// Before (vulnerable):
public function view(AuthUser $authUser, Invoice $invoice): bool
{
    return $authUser->can('View:Invoice');
}

// After (secured):
public function view(AuthUser $authUser, Invoice $invoice): bool
{
    if ((int) $invoice->organization_id !== (int) $authUser->organization_id) {
        return false;
    }

    return $authUser->can('View:Invoice');
}
```

## Next Steps

- Consider adding `organization_id` directly to `EstimatePackage`, `JobMessage`, and `JobTypeChecklistItem` tables to avoid the relation-load overhead in policy checks and to allow global scope enforcement.
- Add Pest feature tests covering cross-org access attempts for each updated policy.
- Review any module-level policies in `Modules/*/Policies/` for the same pattern (e.g. `Modules/BookingModule/Policies/`).
