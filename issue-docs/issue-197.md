# Issue 197

## Issue Summary

Follow-up to issue #131: add a unique constraint on
`organization_settings.organization_id` (one row per org), deduplicate any
existing rows, and guard the Filament Create page against creating a second row.

## Problem

The `organization_settings` table had no unique index on `organization_id`, so
multiple rows could accumulate for the same organization.  Depending on which
row Filament happened to load, integration credentials (Stripe, Twilio,
SendGrid) and panel branding could silently differ between requests.

## Changes Made

### `database/migrations/2026_05_11_000001_add_unique_organization_id_to_organization_settings_table.php`
- **New migration.**
- Deduplicates existing rows: for each `organization_id`, keeps the row with
  the highest `id` (i.e., the most recently inserted) and deletes the rest.
  Uses a portable `WHERE id NOT IN (SELECT MAX(id) … GROUP BY organization_id)`
  approach that works on both SQLite (test) and MySQL (production).
- Adds `UNIQUE(organization_id)` index after deduplication.
- `down()` drops the unique index so the migration is reversible.

### `app/Models/OrganizationSetting.php`
- Added `firstOrCreateForOrganization(int $organizationId): static` — a static
  helper that wraps `firstOrCreate(['organization_id' => $organizationId])`.
  Callers that need a settings record should use this instead of plain `create`,
  ensuring they never produce a second row for the same org.

### `app/Filament/Resources/OrganizationSettingResource/Pages/CreateOrganizationSetting.php`
- Added `mount()` override.  Before delegating to `parent::mount()`, it checks
  whether the authenticated user's organization already has a settings row.  If
  one exists, it calls `$this->redirect(…)` to send the user to the existing
  row's edit page, preventing any possibility of creating a duplicate through
  the UI.

### `tests/Feature/Admin/OrganizationSettingUniqueTest.php`
- **New test file** with four Pest tests:
  1. `firstOrCreateForOrganization returns existing row without creating a duplicate`
  2. `firstOrCreateForOrganization creates a row when none exists`
  3. `create page redirects to edit when org already has a settings row`
  4. `create page renders normally when org has no settings row`

## Next Steps

- Run the full Pest suite in a PHP 8.4 environment (`composer run test`) once
  vendor dependencies are installed.
- Consider hiding the "New Organization Setting" / "Create" button in
  `ListOrganizationSettings` when the current org already has a row, to give
  a cleaner UX (the redirect guard already prevents data corruption, but the
  button is still visible).
