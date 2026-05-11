## Issue Summary
The theme system was still driven by legacy settings fields and raw CSS escape hatches, which prevented TitanPro from behaving like a true design-token engine with inheritance, structured storage, and exportable outputs.

## Root Cause
Theme customization was persisted directly into `platform_settings` fields and optional raw CSS, so there was no canonical primitive → semantic → component token taxonomy, no dedicated token table, and no export pipeline for downstream consumers like CSS, Style Dictionary, or Tailwind. The UI surfaces also exposed raw CSS-oriented behavior instead of treating semantic tokens as the editable source of truth.

## Changes Made
- Added `app/Support/ThemeTokenRegistry.php` to define the token taxonomy and defaults for primitive, semantic, and component tokens.
- Added `app/Support/ThemeTokenManager.php` to seed/load/sanitize token values, sync legacy platform settings into semantic tokens, resolve inheritance, and export CSS / Style Dictionary JSON / Tailwind config payloads.
- Added `app/Models/TitanThemeToken.php` and `database/migrations/2026_05_11_121300_create_titan_theme_tokens_table.php` to persist token definitions in `titan_theme_tokens` with `panel`, `scope`, `key`, and `value` columns plus seeded default rows.
- Added `app/Console/Commands/TitanTokensExportCommand.php` and registered it in `app/Providers/AppServiceProvider.php` so `php artisan titan:tokens:export` writes CSS, JSON, and Tailwind exports.
- Updated `app/Http/Middleware/HandleAppearance.php` and `resources/views/app.blade.php` so the app shell now renders generated CSS custom properties from the token engine and consumes inherited component tokens instead of hardcoded theme variable output.
- Updated `app/Http/Controllers/Platform/SettingsController.php` and `resources/js/pages/Platform/Settings.vue` so the platform settings screen edits semantic tokens (`--color-primary`, `--color-secondary`, `--color-accent`, `--color-surface`, `--font-heading`, `--font-body`) while leaving layout-token serialization in the existing `custom_css` layout block.
- Updated `app/Filament/Pages/SiteSettings.php` so the Filament theme customizer loads and saves semantic tokens via the new token manager instead of treating legacy columns as the primary theme source.
- Updated `app/Filament/Pages/UiStudio.php` and `resources/views/filament/pages/ui-studio.blade.php` to remove the raw custom CSS editing path and replace it with design-token-engine messaging while publishing semantic token values through the new manager.
- Added `tests/Unit/Support/ThemeTokenManagerTest.php` to verify taxonomy grouping, inheritance resolution, and export payload generation.
- Added `tests/Feature/TitanTokensExportCommandTest.php` to cover the export command output files in a Laravel test environment.

## Tests Added or Updated
- Added `tests/Unit/Support/ThemeTokenManagerTest.php`.
- Added `tests/Feature/TitanTokensExportCommandTest.php`.
- Ran `php -l` successfully on all changed PHP files.
- Ran `./vendor/bin/pest tests/Unit/Support/ThemeTokenManagerTest.php` successfully.
- Ran `npx eslint resources/js/pages/Platform/Settings.vue` successfully.
- Full `npm run build` and app-boot-dependent validation remain blocked in this sandbox by a pre-existing `php artisan` boot failure from Filament Shield config resolution.

## Next Steps
- Resolve the existing `php artisan` boot failure (`BezhanSalleh\FilamentShield\Support\ShieldConfig` string conversion) and rerun full validation including `php artisan titan:tokens:export`, `npm run build`, and relevant feature tests.
- If panel-specific theming is needed next, extend the current token editor UIs to expose the already-supported `panel` scope in `titan_theme_tokens`.
