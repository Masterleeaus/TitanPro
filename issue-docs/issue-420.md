# Issue 420 — Full Theme Export Engine

## Files Changed
- `app/Support/ThemeExportManager.php`
- `app/Console/Commands/TitanThemeExportCommand.php`
- `app/Providers/AppServiceProvider.php`
- `app/Filament/Pages/UiStudio.php`
- `resources/views/filament/pages/ui-studio.blade.php`
- `tests/Feature/TitanThemeExportCommandTest.php`

## Fixes Applied
- Added a dedicated export manager that supports:
  - Theme ZIP (`theme.json`, `meta.json`, `preview.png`)
  - UI Pack (theme + component overrides + dashboard layout)
  - Branding Kit (brand metadata + colors + fonts + logo/favicon when available)
  - Tenant Preset (theme + role profiles + menus + dashboard layouts + component overrides)
  - CSS export (`:root` token variables)
  - Style Dictionary JSON export
- Added `php artisan titan:export:theme {name} {format}` command with optional output path override.
- Registered the new command in `AppServiceProvider`.
- Added a UI Studio **Export** tab with:
  - format selector
  - single download action
- Wired UiStudio export actions to the shared export manager.
- Added focused feature tests for command output files and ZIP contents.

## Next Steps
- Run full Pest suite and UI smoke checks in an environment with installed Composer/Node dependencies.
- Add browser-level test coverage for the new UI Studio Export tab interactions.
