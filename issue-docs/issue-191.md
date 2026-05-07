# Issue 191

## Issue Summary

Added tenant boundary support to technician location storage and org-scoped reads so driver location data no longer crosses organization boundaries.

## Root Cause

The `driver_locations` table stored only `user_id`, so location queries and admin resources had no first-class organization boundary to enforce. That allowed shared-table reads to rely on indirect filtering and left Filament resource queries unscoped.

## Changes Made

- Added migration `2026_05_07_121600_add_organization_id_to_driver_locations_table.php` to add nullable `organization_id`, add an index, attach the foreign key, and backfill existing rows from `users.organization_id`.
- Updated `App\Models\DriverLocation` to allow mass assignment of `organization_id`, infer it on create when omitted, expose an `organization()` relation, and provide a reusable `forOrganization()` scope.
- Updated technician location writes in `App\Http\Controllers\Technician\LocationController` to persist `organization_id` from the authenticated user.
- Updated org-scoped location reads in `App\Http\Controllers\Owner\DispatchController` and `App\Filament\Resources\DriverLocationResource`.
- Updated `App\Console\Commands\PruneDriverLocations` to backfill missing `organization_id` values before pruning legacy rows.
- Updated `Modules\TitanCore\Console\Commands\ModulesDoctorCommand` to flag missing `driver_locations.organization_id` support needed for org-scoped technician location queries.

## Tests Added or Updated

- Updated `tests/Feature/Technician/LocationTest.php` to assert stored locations include `organization_id`.
- Updated `tests/Feature/Owner/DispatchTest.php` to verify one organization’s admin map does not include another organization’s technician location.
- Added `tests/Feature/ModulesDoctorDriverLocationsTest.php` to verify `modules:doctor` flags a missing `organization_id` column on `driver_locations`.
- Updated `tests/Feature/Admin/OrgScopingTest.php` to verify the Filament admin driver-locations edit page returns 404 when the record belongs to a different organization.

## Next Steps

- Re-run the focused Pest tests and `modules:doctor` in a PHP 8.4 environment with dependencies installed, since this sandbox does not currently have `vendor/` and only provides PHP 8.3.
