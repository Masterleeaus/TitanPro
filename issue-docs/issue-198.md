# Issue 198 — UI Studio Live Preview Sandbox

## Files Changed

| File | Summary |
|---|---|
| `app/Filament/Pages/UiStudio.php` | Added live preview state (`previewPanel`, `previewFrameSize`, `syncPreviewScroll`), panel option resolution from `config/titan_panels.php`, preview URL/CSS-variable payload helpers, and unsaved-theme snapshot tracking. |
| `resources/views/filament/pages/ui-studio.blade.php` | Reworked layout to a 40/60 controls/preview split, added sandboxed iframe preview toolbar (panel selector, Desktop/Tablet/Mobile toggle, sync-scroll toggle, unsaved indicator), switched component panel selector to config-driven options, and added postMessage-based iframe theme sync script. |
| `issue-docs/issue-198.md` | Implementation notes for this issue. |

## Fixes Applied

1. Implemented split-screen composition with controls on the left side and a dedicated live iframe preview on the right side.
2. Added sandboxed preview iframe that targets the active/selected Filament panel path and keeps navigation inside the preview frame.
3. Added postMessage-based CSS custom property updates to the iframe document so color/font token edits apply live without iframe reload.
4. Added responsive preview frame toggles (Desktop / Tablet / Mobile).
5. Added sync-scroll toggle to align controls scrolling with preview scrolling.
6. Added unsaved-theme indicator by diffing current theme state against the last published snapshot.
7. Replaced hardcoded panel options with canonical panel metadata from `config('titan_panels.panels')` (filtered by current user roles).

## Validation Notes

- `php -l app/Filament/Pages/UiStudio.php` passes.
- Full PHP test/build execution is blocked in this sandbox because the repository requires PHP 8.4 while the environment provides PHP 8.3.6.
- Frontend repo-wide checks have existing unrelated baseline failures (`npm run format:check`, `npm run lint`, and `npm run build` via Wayfinder/Artisan dependency path).

## Next Steps

1. Run full project validation in a PHP 8.4 environment (`composer run test`, app boot, and Vite build with Wayfinder generation).
2. Perform in-browser QA pass in a running Filament session for each panel option to confirm permissions and navigation expectations.
3. If needed, broaden theme var mapping to additional panel-specific CSS token names once visual QA identifies gaps.

---

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
# Issue 198 — Tailwind v4 Configuration Alignment

## Files Changed

| File | Change |
|------|--------|
| `resources/css/app.css` | Migrated to Tailwind v4 CSS-first setup (`@import`, `@source`, `@theme`, `@plugin`, dark variant declaration). |
| `tailwind.config.js` | Removed legacy Tailwind v3-style JS config. |
| `package.json` | Removed duplicate `tailwindcss` v3 devDependency so Tailwind v4 is the active version. |

## Fixes Applied

1. Replaced legacy `@tailwind base/components/utilities` directives with Tailwind v4 `@import 'tailwindcss'`.
2. Moved content scanning paths from JS config into `@source` directives in `resources/css/app.css`.
3. Ported the custom sans font stack into a v4 `@theme` block.
4. Replaced JS plugin registration with CSS plugin directive `@plugin '@tailwindcss/forms'`.
5. Added a class-based dark mode variant declaration compatible with the project’s `.dark` strategy.
6. Removed `tailwind.config.js` to avoid v3-format config drift.

## Validation Notes

- Confirmed Tailwind compiles successfully with:
  - `npx @tailwindcss/cli -i ./resources/css/app.css -o /tmp/tw-after.css`
- Full Vite build in this sandbox remains blocked by missing/unsupported PHP runtime dependencies for `artisan wayfinder:generate`.

## Next Steps

1. Run `npm install` in CI or a PHP 8.4-compatible environment to refresh lock state with Tailwind v4 as the resolved version.
2. Run `npm run build` in CI after Composer dependencies are available to confirm no Tailwind warnings in the full Vite pipeline.
3. If additional legacy Tailwind JS config existed in module assets, migrate those to CSS-first v4 directives as a follow-up.
