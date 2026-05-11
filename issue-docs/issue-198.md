## Issue Summary
Wired TitanNexus motion schema output into the active Filament panel runtime shell so generated motion CSS is now injected where panel theme styles are rendered.

## Files Changed
- `Modules/TitanNexus/UI/Themes/MotionRuntimeTheme.php`
- `app/Providers/Filament/Concerns/RegistersFilamentPlugins.php`
- `resources/views/filament/ui-inspector.blade.php`
- `Modules/TitanNexus/UI/Tests/UI/UiKitStructureTest.php`

## Fixes Applied
- Added `MotionRuntimeTheme` as a shared runtime payload builder that resolves motion CSS from the Motion tab schema (`ModuleSettingsForm`) with safe fallback to `UiTokens`.
- Updated the shared Filament render hook (`uiInspectorHook`) to pass runtime motion theme payload into the panel shell view on every active panel.
- Injected motion CSS into the rendered panel shell (`filament.ui-inspector`) via a dedicated `<style>` tag, and set `documentElement.dataset.motionPreset` from configured `--motion-preset` (with fallback) so preset selectors activate at runtime.
- Kept reduced-motion behavior intact by injecting the exact generated CSS that already contains the `prefers-reduced-motion` override.
- Added renderer-path coverage in `UiKitStructureTest` to assert runtime payload CSS and reduced-motion metadata match the Motion tab schema output.

## Validation Notes
- `php -l` passed for all changed PHP files.
- `composer run test`, `./vendor/bin/pest`, and `vendor/bin/pint` are blocked in this environment because dependencies cannot be installed under PHP 8.3.6 while `composer.json` requires PHP `^8.4`.

## Next Steps
- Re-run `composer run test` and `vendor/bin/pint` in a PHP 8.4 environment to complete required full validation.
- Optionally add an end-to-end browser assertion that active Filament panel HTML contains `#titan-motion-runtime-theme-css` and motion preset data-attribute wiring.
