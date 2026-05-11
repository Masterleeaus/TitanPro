# Issue 132

## Issue Summary

Follow-up to issue 131: `stripe_publishable_key` was still rendered in plain text in the
`OrganizationSettingResource` admin panel. Even though publishable keys are not strictly
secret, exposing them in admin lists provides a fingerprinting signal that reveals which
Stripe environments are wired up.

## Changes Made

### `app/Models/OrganizationSetting.php`
- Added `maskPrefix(string $value, int $prefixLength = 12): ?string` static method.
  Shows the first `$prefixLength` characters of the key followed by `****`, e.g.
  `pk_live_abcd****`. This preserves the key-type prefix (informative) while hiding the
  unique portion that acts as a fingerprint.

### `app/Filament/Resources/OrganizationSettingResource.php`
- Added `stripe_publishable_key` `TextColumn` to the table with `formatStateUsing`
  calling `OrganizationSetting::maskPrefix()`. Column is toggleable so admins can
  hide it when not needed.
- Changed the `stripe_publishable_key` form `TextInput` to use `->password()->revealable()`
  so the edit form uses a reveal-on-click pattern, consistent with `StripeSettings.php`,
  while still permitting full view and edit of the value.

### `tests/Feature/Owner/SettingsTest.php`
- Added four unit tests covering `OrganizationSetting::maskPrefix()`:
  - Returns `null` for `null` input.
  - Shows first 12 characters + `****` for a typical publishable key.
  - Appends `****` even when the value is shorter than the prefix length.
  - Respects a custom `$prefixLength` argument.

## Next Steps

- Run the full Pest suite in a PHP 8.4 environment: `composer run test`.
- Consider whether `stripe_publishable_key` should also be encrypted at rest (currently
  it is stored as plain text); adding it to the `casts` array would require a data
  migration for existing rows.
- Evaluate adding `stripe_publishable_key` to the `$hidden` array once encryption is
  applied so it is excluded from serialised API responses.
