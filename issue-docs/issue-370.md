# Issue 370

## Files Changed

- `app/Filament/Resources/OrganizationSettingResource/Pages/ListOrganizationSettings.php`
- `app/Filament/Resources/OrganizationSettingResource/Pages/CreateOrganizationSetting.php`
- `tests/Feature/Admin/OrganizationSettingUniqueTest.php`

## Fixes Applied

- Replaced the unrestricted Filament create button with a single `Initialise settings` action.
- Hid that action once the current organization already has an `OrganizationSetting` row.
- Updated the create route to use `OrganizationSetting::firstOrCreateForOrganization()` and always redirect to the edit page for the canonical row.
- Added feature coverage for the initialise-and-redirect flow, the action visibility on the index page, and the database unique constraint blocking a second direct insert for the same organization.

## Next Steps

- Run the targeted Pest file and full relevant suite in a PHP 8.4 environment with installed Composer dependencies.
- If needed, manually verify the Filament UI in-browser to confirm the `Initialise settings` button disappears after the first row is created.
