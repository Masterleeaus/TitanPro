# Issue #321 — Add dedicated `ui-inspector.manage` permission for Visual UI Inspector

## Summary

Follow-up to issue #193, which introduced the Visual UI Inspector and guarded its API
routes with `role:super_admin|admin|owner`. This issue replaces that coarse role-check
with a dedicated Spatie permission (`ui-inspector.manage`), so operators can grant
inspector access to specific staff (e.g. a designer role) without giving full admin
or owner privileges.

---

## Files Changed

| File | Change |
|------|--------|
| `database/seeders/RolesAndPermissionsSeeder.php` | Added `ui-inspector.manage` to the seeded permissions list; `super_admin`, `admin`, and `owner` receive it automatically via `syncPermissions(Permission::all())` |
| `routes/web.php` | Switched UI Inspector route group middleware from `role:super_admin\|admin\|owner` to `permission:ui-inspector.manage` |
| `app/Http/Controllers/UiInspectorController.php` | Added `__construct()` with `$this->middleware('can:ui-inspector.manage')` as a defence-in-depth gate |
| `tests/Feature/UiInspectorPermissionTest.php` | New Pest feature tests (see below) |
| `issue-docs/issue-321.md` | This file |

---

## Changes in Detail

### `database/seeders/RolesAndPermissionsSeeder.php`

Added `'ui-inspector.manage'` to the `$permissions` array under a new `// Visual UI Inspector`
comment. Because `super_admin`, `admin`, and `owner` all call `syncPermissions(Permission::all())`,
they inherit the new permission automatically.

### `routes/web.php`

```php
// Before
Route::middleware(['auth', 'role:super_admin|admin|owner'])

// After
Route::middleware(['auth', 'permission:ui-inspector.manage'])
```

### `app/Http/Controllers/UiInspectorController.php`

Added constructor with `can:` middleware for defence-in-depth:

```php
public function __construct()
{
    $this->middleware('can:ui-inspector.manage');
}
```

---

## Tests Added

`tests/Feature/UiInspectorPermissionTest.php` covers:

| Test | Assertion |
|------|-----------|
| User with permission (no admin role) can GET /overrides | 200 |
| User with permission (no admin role) can POST /overrides | 200 |
| User with permission (no admin role) can DELETE /overrides/{key} | 200 |
| User with permission (no admin role) can DELETE /overrides | 200 |
| Authenticated user **without** permission gets 403 on GET | 403 |
| Authenticated user **without** permission gets 403 on POST | 403 |
| Authenticated user **without** permission gets 403 on DELETE | 403 |
| Unauthenticated user is redirected on GET | 401 (JSON) |
| `super_admin` role can GET overrides (backward-compat) | 200 |
| `admin` role can GET overrides (backward-compat) | 200 |
| `owner` role can GET overrides (backward-compat) | 200 |

---

## Acceptance Criteria Coverage

| Criterion | Status |
|-----------|--------|
| New permission `ui-inspector.manage` seeded | ✅ |
| Routes switched from `role:…` to `permission:ui-inspector.manage` | ✅ |
| `super_admin`, `admin`, `owner` seeded with permission | ✅ (via `syncPermissions(Permission::all())`) |
| `UiInspectorController` gate check as defence-in-depth | ✅ |
| Test: user with permission (no admin role) can call API | ✅ |
| Test: user without permission gets 403 | ✅ |

---

## Next Steps

- Run `composer run test` in a PHP 8.4 environment to execute the full suite.
- To grant the inspector to a designer role, operators can do:
  ```php
  $designer = Role::firstOrCreate(['name' => 'designer']);
  $designer->givePermissionTo('ui-inspector.manage');
  ```
# Issue 321 — Replace TitanNexus placeholder pages with org-scoped Filament Resources

## Files Changed

- `app/Providers/Filament/TitanNexusPanelProvider.php`
  - Removed placeholder page registrations/imports so TitanNexus now relies on discovered resources.
- `app/Filament/TitanNexus/Pages/Verticals.php` (removed)
- `app/Filament/TitanNexus/Pages/LeadPipeline.php` (removed)
- `app/Filament/TitanNexus/Pages/TrainingContent.php` (removed)
- `app/Filament/TitanNexus/Pages/MarketingCampaigns.php` (removed)
- `resources/views/filament/titan-nexus/pages/verticals.blade.php` (removed)
- `resources/views/filament/titan-nexus/pages/lead-pipeline.blade.php` (removed)
- `resources/views/filament/titan-nexus/pages/training-content.blade.php` (removed)
- `resources/views/filament/titan-nexus/pages/marketing-campaigns.blade.php` (removed)

### New domain models
- `app/Models/VerticalPack.php`
- `app/Models/LeadPipelineEntry.php`
- `app/Models/TrainingContentModule.php`
- `app/Models/MarketingCampaign.php`

### New migrations
- `database/migrations/2026_05_11_180300_create_vertical_packs_table.php`
- `database/migrations/2026_05_11_180301_create_lead_pipeline_entries_table.php`
- `database/migrations/2026_05_11_180302_create_training_content_modules_table.php`
- `database/migrations/2026_05_11_180303_create_marketing_campaigns_table.php`

### New factories
- `database/factories/VerticalPackFactory.php`
- `database/factories/LeadPipelineEntryFactory.php`
- `database/factories/TrainingContentModuleFactory.php`
- `database/factories/MarketingCampaignFactory.php`

### New TitanNexus Filament resources
- `app/Filament/TitanNexus/Resources/VerticalPackResource.php`
- `app/Filament/TitanNexus/Resources/LeadPipelineEntryResource.php`
- `app/Filament/TitanNexus/Resources/TrainingContentModuleResource.php`
- `app/Filament/TitanNexus/Resources/MarketingCampaignResource.php`
- `app/Filament/TitanNexus/Resources/**/Pages/*.php` (list/create/view/edit pages for each resource)

### New tests
- `tests/Feature/TitanNexus/TitanNexusResourcesTest.php`

## Fixes Applied

- Replaced static TitanNexus placeholders with four full Filament resources backed by dedicated domain models.
- Added CRUD pages (list/create/view/edit) for vertical packs, lead pipeline entries, training modules, and marketing campaigns.
- Added explicit org scoping in each resource via `getEloquentQuery()` with null-safe `whereRaw('1 = 0')` fallback.
- Added role checks in each resource so only owner/admin can use resource actions (super-admin denied).
- Preserved existing TitanNexus routes by setting slugs to:
  - `verticals`
  - `lead-pipeline`
  - `training-content`
  - `marketing-campaigns`
- Added Pest feature tests for owner/admin CRUD route access, super-admin denial, and cross-org 404 isolation per resource.

## Validation

- `php -l` run across all changed PHP files: **pass**.
- Could not run Pest in this environment because `vendor/` is unavailable and Composer install is blocked on PHP 8.3.6 while project dependencies require PHP 8.4+.

## Next Steps

- In a PHP 8.4+ environment: run migrations and execute `./vendor/bin/pest tests/Feature/TitanNexus/TitanNexusResourcesTest.php`.
- Run full project test suite once dependencies are installed.
- If desired, add resource-specific policy classes for finer-grained action control beyond panel role gating.
# Issue 321 — [FOLLOW-UP] TitanPro dedicated super-admin resources

## Files Changed

- `app/Providers/Filament/TitanProPanelProvider.php`
- `app/Models/Organization.php`
- `database/migrations/2026_05_11_180500_add_module_enablement_and_suspension_to_organizations_table.php`
- `app/Filament/TitanPro/Resources/OrganizationResource.php`
- `app/Filament/TitanPro/Resources/OrganizationResource/Pages/ListOrganizations.php`
- `app/Filament/TitanPro/Resources/OrganizationResource/Pages/CreateOrganization.php`
- `app/Filament/TitanPro/Resources/OrganizationResource/Pages/EditOrganization.php`
- `app/Filament/TitanPro/Resources/UserResource.php`
- `app/Filament/TitanPro/Resources/UserResource/Pages/ListUsers.php`
- `app/Filament/TitanPro/Resources/UserResource/Pages/CreateUser.php`
- `app/Filament/TitanPro/Resources/UserResource/Pages/EditUser.php`
- `app/Filament/TitanPro/Resources/SubscriptionResource.php`
- `app/Filament/TitanPro/Resources/SubscriptionResource/Pages/ListSubscriptions.php`
- `app/Filament/TitanPro/Resources/SubscriptionResource/Pages/CreateSubscription.php`
- `app/Filament/TitanPro/Resources/SubscriptionResource/Pages/EditSubscription.php`
- `app/Filament/TitanPro/Resources/ModuleResource.php`
- `app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php`
- `app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php`
- `app/Filament/TitanPro/Pages/PlatformHealthDashboard.php`
- `resources/views/filament/titanpro/pages/platform-health-dashboard.blade.php`
- `tests/Feature/Admin/TitanProSuperAdminResourcesAccessTest.php`

## Fixes Applied

- Switched the TitanPro panel to discover resources/pages from `app/Filament/TitanPro/**` instead of generic `app/Filament/Resources`.
- Added dedicated TitanPro resources for:
  - cross-org organization management (including suspend/unsuspend actions)
  - cross-org user management (organization + role filters, role assignment, impersonate action)
  - cross-org subscription oversight
  - per-tenant module enablement (`enabled_modules` on organizations)
- Added a TitanPro platform health page showing queue depth, failed jobs, last-hour failures, and error-rate snapshot.
- Added resource-level super-admin authorization methods (`canViewAny`, `canCreate`, `canView`, `canEdit`, `canDelete`, `canDeleteAny`) across all new TitanPro resources.
- Added a focused Pest feature test ensuring:
  - `super_admin` can access TitanPro dedicated resource/page routes
  - non-super-admin (`admin`) receives `403` on those routes

## Validation Notes

- Baseline environment checks were run:
  - `composer run test` failed due missing `vendor/` (no Composer deps in sandbox)
  - `npm run build` failed (`vite` missing)
  - `npm run lint` failed (`eslint` missing)
- Post-change checks run:
  - `php -l` on all changed PHP files passed
  - targeted Pest command `./vendor/bin/pest tests/Feature/Admin/TitanProSuperAdminResourcesAccessTest.php` could not run because `vendor/bin/pest` is unavailable in this sandbox

## Next Steps

- Run `composer install` in a PHP 8.4-compatible environment and execute:
  - `./vendor/bin/pest tests/Feature/Admin/TitanProSuperAdminResourcesAccessTest.php`
  - full test suite (`composer run test`)
- Optionally wire impersonation stop/return flow for `titanpro.impersonator_id` session key in UI.
- If tenant module enablement should drive runtime gating immediately, connect `organizations.enabled_modules` to module resolution checks.
