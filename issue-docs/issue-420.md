# Issue 420 — UI Theme Versioning and History

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
