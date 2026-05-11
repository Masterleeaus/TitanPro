## Issue Summary
- Verified and hardened Menu Studio integration for Filament admin navigation.
- Fixed Menu Manager plugin registration and menu schema compatibility required for menu CRUD flows.
- Added focused feature tests for menu item add/delete persistence and navigation-order retrieval.

## Root Cause
- The Menu Manager plugin class namespace in `AdminPanelProvider` used incorrect casing, preventing reliable plugin loading on case-sensitive environments.
- The `fmm_menus` schema did not define a `slug` column while a later migration attempted to add a unique index on it, causing migration/runtime incompatibility for menu persistence paths.

## Changes Made
- Updated Menu Manager plugin registration to `NoteBrainsLab\\FilamentMenuManager\\FilamentMenuManagerPlugin`.
- Added nullable `slug` column to initial `fmm_menus` table creation migration for fresh installs.
- Updated slug-uniqueness migration to:
  - use configured table prefix
  - add `slug` column if it is missing (for existing installs)
  - then apply unique index on `slug`
- Added `tests/Feature/MenuStudioPersistenceTest.php` covering:
  - add item persistence + ordered nav retrieval
  - delete item removal from nav query
  - slug column availability for migration compatibility
- Updated `config/filament-shield.php` to the current Filament Shield v4 config schema so Laravel package discovery no longer crashes on `auth_provider_model` during PHP 8.4 bootstrap.

## Tests Added or Updated
- Added `tests/Feature/MenuStudioPersistenceTest.php` with 3 feature tests.
- Attempted baseline validation in this environment:
  - `npm install` succeeded.
  - `npm run lint` is still red at baseline with thousands of pre-existing ESLint errors unrelated to Menu Studio.
  - A PHP 8.4 containerized Composer install now resolves dependencies and populates `vendor/`, but Laravel bootstrap remains blocked by additional pre-existing app issues after the Shield config fix.
  - GitHub Actions `Production Readiness Gate` on `main` (`run_id=25670690049`) fails on the same Composer/package discovery bootstrap path, confirming the baseline is currently red upstream as well.

## Follow-up Verification Attempt (2026-05-11)
- Files changed in this follow-up:
  - `config/filament-shield.php`
  - `issue-docs/issue-155.md`
- Fixes applied in this follow-up:
  - Migrated the published Shield config from the older nested `auth_provider_model` format to the current schema expected by the installed package.
  - Restored Shield config sections (`panel_user`, `permissions`, `policies`, `resources`, `pages`, `widgets`, `custom_permissions`, `register_role_policy`) required by the current package version.
- Verification findings:
  - The Menu Studio slug migration is still present in code:
    - `database/migrations/2026_04_28_053533_create_menus_table.php` defines the nullable `slug` column.
    - `database/migrations/2026_04_28_064358_make_menus_slug_unique.php` adds the unique index on `slug`.
  - After the Shield config update, package discovery proceeds past the prior `ShieldConfig` object-to-string crash but now stops on a separate pre-existing Filament v4 compatibility issue in `app/Filament/ZeroPay/Pages/StripeSettings.php` (`$navigationGroup` type mismatch).
  - Because the app still cannot fully bootstrap under PHP 8.4, end-to-end Menu Studio CRUD verification in the admin panel could not be completed in this session.

## Next Steps
- Resolve the remaining PHP 8.4 / Filament bootstrap blocker in `app/Filament/ZeroPay/Pages/StripeSettings.php`.
- Once the app boots cleanly, re-run in a PHP 8.4 environment with installed dependencies:
  - `composer run test`
  - `vendor/bin/pint`
  - `npm run lint`
- Manually verify in admin panel:
  - open Menu Studio page
  - add/edit/reorder/delete items
  - confirm updated items appear in panel nav after reload.
