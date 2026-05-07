# Issue 131

## Issue Summary

`OrganizationSettingResource` had no org scoping, allowing any admin user to list, view,
and overwrite `OrganizationSetting` records belonging to other organizations — including
sensitive integration credentials such as Stripe, Twilio, and SendGrid keys.

## Root Cause

1. **Missing `getEloquentQuery()` override** — the default Eloquent query returned all rows
   from `organization_settings` with no `WHERE organization_id = ?` clause.  An admin from
   org A could therefore navigate to `/admin/organization-settings` and see every record in
   the table.

2. **Editable `organization_id` field in the form** — the create/edit form rendered
   `organization_id` as a free-text numeric input, so an authenticated user could reassign
   a settings record to an arbitrary organization, or create a record with a foreign
   org ID.

3. **No ownership check in the Policy** — `OrganizationSettingPolicy::view()`,
   `update()`, and `delete()` only verified a permission gate; they did not confirm that
   the record being accessed belonged to the authenticated user's organization.

## Changes Made

### `app/Filament/Resources/OrganizationSettingResource.php`
- Added `use Illuminate\Database\Eloquent\Builder` import.
- Added `getEloquentQuery()` that scopes every query to
  `auth()->user()?->organization_id`, mirroring the pattern used by
  `CustomerResource`, `PropertyResource`, `ItemResource`, and others.
- Removed the editable `organization_id` field from the form schema, eliminating
  the ability to manually set or change which organization a settings record belongs to.
- Removed the `organization_id` column from the list table (it is always the current
  tenant's org, so it adds no information and exposes internal IDs to the UI).

### `app/Filament/Resources/OrganizationSettingResource/Pages/CreateOrganizationSetting.php`
- Added `mutateFormDataBeforeCreate()` to automatically inject
  `organization_id` from the authenticated user before persisting a new record,
  ensuring the field is always set correctly without relying on user input.

### `app/Policies/OrganizationSettingPolicy.php`
- Updated `view()`, `update()`, and `delete()` to add an org-ownership guard
  (`$organizationSetting->organization_id === $authUser->organization_id`).
  This is a defence-in-depth measure: even if the query scoping were somehow
  bypassed, the policy prevents cross-org access.

### `tests/Feature/Admin/OrgScopingTest.php`
- Added `organization-settings edit page 404s for other-org record` test that
  creates a setting owned by a second organization and asserts that a user from
  the first organization receives a 404 when attempting to access the edit page.

## Next Steps

- Run the full Pest suite in a PHP 8.4 environment once vendor dependencies are
  installed: `composer run test` (current sandbox has PHP 8.3 and no vendor/).
- Consider restricting the `Create` action on the resource (an org should typically
  have exactly one `OrganizationSetting` row); a `firstOrCreate` guard or a unique
  constraint on `organization_id` would prevent duplicate rows.
- Evaluate whether `stripe_publishable_key` should also be masked in the table view,
  since even public keys reveal integration details.
