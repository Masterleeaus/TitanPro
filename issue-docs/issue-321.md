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
