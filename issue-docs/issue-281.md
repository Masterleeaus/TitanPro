## Issue #281 — [FOLLOW-UP] Register UI Studio page in TitanStudio, TitanNexus, and other panel providers

### Files Changed

- `app/Providers/Filament/TitanNexusPanelProvider.php`
- `app/Providers/Filament/TitanQuotesPanelProvider.php`
- `app/Providers/Filament/GroundZeroPanelProvider.php`
- `app/Providers/Filament/ZeroPayPanelProvider.php`
- `app/Filament/Pages/UiStudio.php`
- `tests/Feature/Admin/UiStudioPanelAccessTest.php`
- `issue-docs/issue-281.md`

### Fixes Applied

1. Registered `UiStudio` in additional tenant panel providers:
   - TitanNexus
   - TitanQuotes
   - GroundZero
   - ZeroPay
2. Confirmed TitanStudio already discovers its own `UiStudio` page via `app/Filament/TitanStudio/Pages`.
3. Added `UiStudio::canAccess()` role gating so in registered tenant panels (`titanstudio`, `titannexus`, `titanquotes`, `groundzero`, `zeropay`) only `owner` and `admin` can open UI Studio.
4. Added Pest feature coverage for:
   - owner/admin access in registered panels
   - dispatcher/bookkeeper denial where panel access exists but UI Studio should remain owner/admin-only
   - denial in a non-registered panel route (`/titango/ui-studio`)

### Validation Notes

- `php -l` passed for all changed PHP files.
- Targeted Pest run currently fails at application bootstrap due an existing Filament Shield config issue (`auth_provider_model` object casting error) unrelated to this change.

### Next Steps

1. Resolve the existing Filament Shield bootstrap/config error so Feature tests can run.
2. Re-run:
   - `./vendor/bin/pest tests/Feature/Admin/UiStudioPanelAccessTest.php`
   - full relevant suite after bootstrap is restored.
