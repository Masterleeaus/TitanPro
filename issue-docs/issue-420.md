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

---

# Issue 420 (PR update) — UI Theme Versioning and History

## Files Changed
- `app/Filament/Pages/UiStudio.php`
- `resources/views/filament/pages/ui-studio.blade.php`
- `app/Models/TitanThemeVersion.php`
- `database/migrations/2026_05_17_161600_create_titan_theme_versions_table.php`
- `app/Console/Commands/TitanThemeRollbackCommand.php`
- `app/Providers/AppServiceProvider.php`
- `tests/Unit/UiStudioThemeVersioningTest.php`

## Fixes Applied
- Added persistent theme version storage in `titan_theme_versions` with `org_id`, `panel`, `version_number`, `token_snapshot`, `label`, `created_by`, and `created_at`.
- Added model-level snapshot creation with automatic pruning to keep the most recent 50 versions per org/panel.
- Added automatic snapshot creation before AI generation and before bulk preset/theme installs.
- Added version creation on publish/save, including custom labels for named snapshots.
- Added UI Studio theme history panel with:
  - named snapshot input
  - version list with label/date/author
  - one-click rollback action
  - side-by-side diff view with changed tokens highlighted
- Added rollback behavior that restores selected version state and saves as a new version labeled `Rollback from vN`.
- Added CLI rollback command: `php artisan titan:theme:rollback {org} {version}` (with optional `--panel`).
- Registered rollback command in `AppServiceProvider`.
- Added focused unit coverage checking that versioning hooks, migration schema, and rollback command signature exist.

## Next Steps
- Run migrations in an environment with full PHP/composer dependencies.
- Validate full UI Studio flow interactively (save, named snapshot, diff, rollback, auto-snapshots).
- Run full test suite and frontend build/lint once dependencies are installed.
