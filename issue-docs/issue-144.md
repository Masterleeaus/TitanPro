## Issue Summary
Implemented a Brand Engine flow in Site Settings to generate a complete design theme from uploaded brand assets (logo, font, wallpaper, accent), preview it before applying, run WCAG contrast checks, and persist a named snapshot.

## Root Cause
The existing branding settings only allowed manual color/logo edits and had no automated extraction/generation pipeline, no contrast validation, no wallpaper/font token support, and no snapshot history for generated brand themes.

## Changes Made
- Added `app/Support/BrandThemeGenerator.php` to:
  - extract dominant colors from uploaded logo/wallpaper (SVG + raster support),
  - map colors to `primary`, `secondary`, `surface`,
  - infer fonts from Google Fonts URL or uploaded font filename,
  - compute WCAG contrast ratio and warning.
- Updated `app/Filament/Pages/SiteSettings.php`:
  - added Brand Engine inputs (font file/url, wallpaper),
  - added one-click **Generate from brand** action,
  - populated generated token fields and in-page preview state,
  - saved generated snapshot as `Brand: {OrgName} — auto` in `theme_snapshots`.
- Updated `resources/views/filament/pages/site-settings.blade.php`:
  - added generated-theme preview card and WCAG warning display before save/apply.
- Extended platform settings persistence:
  - `database/migrations/2026_05_07_121500_add_brand_engine_fields_to_platform_settings.php`
  - `app/Models/PlatformSetting.php`
  - new fields: `surface_color`, `font_heading`, `font_body`, `font_source_url`, `font_path`, `bg_image_path`, `theme_snapshots`.
- Updated shared appearance payload:
  - `app/Http/Middleware/HandleAppearance.php` now shares new branding tokens/asset fields.
- Updated app shell style application:
  - `resources/views/app.blade.php` now emits CSS variables:
    - `--color-primary`, `--color-secondary`, `--color-surface`,
    - `--font-heading`, `--font-body`,
    - `--bg-image`,
  - loads Google Fonts URL when valid,
  - registers uploaded font via `@font-face`,
  - applies wallpaper background and typography.

## Tests Added or Updated
- Added `tests/Unit/Support/BrandThemeGeneratorTest.php`:
  - validates color/font extraction + generated token mapping,
  - validates Google Fonts URL sanitization behavior.
- Ran `php -l` syntax checks on all changed PHP files successfully.

## Next Steps
- Run migrations in a PHP 8.4 environment.
- Run full validation (`composer run test`, `vendor/bin/pint`, `npm run build`) after installing PHP dependencies.
- Manually verify in Filament Site Settings by uploading assets, generating theme preview, confirming snapshot creation, and applying the generated theme.

## Follow-up verification attempt (2026-05-11)

### Files changed in this follow-up
- `issue-docs/issue-144.md`

### Verification steps executed
- `composer run test` → failed because `vendor/autoload.php` is missing.
- `vendor/bin/pint` → failed because `vendor/` is not installed.
- `npm run build` → failed because Vite is not installed before Node dependencies are installed.
- `npm install --no-audit --no-fund` → succeeded.
- `npm run build` (after Node install) → failed because Wayfinder calls `php artisan`, which still requires missing `vendor/autoload.php`.
- `composer install --no-interaction --no-progress` → failed because the workspace runtime is PHP 8.3.6 while `composer.json` requires PHP `^8.4`.

### Fixes applied
- No product code changes were applied in this follow-up. This run only re-validated the execution environment and documented blockers.

### Current blocker
- End-to-end Filament manual verification cannot be completed in this runner because PHP 8.4 is required to install Composer dependencies and bootstrap Laravel.

### Next steps
- Re-run this follow-up in a PHP 8.4+ environment with Composer dependencies installed.
- Then execute the manual acceptance flow in Filament Site Settings and capture UI evidence for:
  - generated token population,
  - preview rendering with wallpaper/fonts,
  - WCAG warning behavior,
  - `theme_snapshots` persistence,
  - emitted CSS variables and font loading on next page load.
