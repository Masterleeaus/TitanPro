## Issue Summary

Implemented end-to-end browser validation for the TitanNexus UI Studio motion layer. The motion
preset, speed, and easing controls — together with their generated CSS and `prefers-reduced-motion`
accessibility override — were previously tested only with PHP unit-level structure tests. This issue
adds a full browser test harness (Laravel Dusk + PHP 8.4 CI) that exercises the live Motion preview
page in a real Chromium instance.

## Root Cause

No browser test infrastructure existed. The `production-check.yml` CI workflow also specified PHP 8.2,
which is incompatible with the project's `php: ^8.4` constraint (causing every composer install in CI
to fail). Without a running Laravel server + Chrome, it was impossible to validate that CSS variables
actually reached the DOM, that JavaScript event handlers updated live preview state, or that the
`prefers-reduced-motion` stylesheet override disabled animations.

## Changes Made

| File | Change |
|------|--------|
| `.github/workflows/production-check.yml` | Fixed PHP version: `8.2` → `8.4` |
| `.github/workflows/motion-browser.yml` | **New** — CI workflow: PHP 8.4 + ChromeDriver + Dusk running `tests/Browser/MotionBrowserTest.php` |
| `composer.json` | Added `laravel/dusk: ^8.6` to `require-dev` |
| `app/Http/Controllers/UiStudio/MotionPreviewController.php` | **New** — invokable controller that hydrates `UiTokens::make()` + `ModuleSettingsForm::schema()` into the motion preview Blade view |
| `resources/views/ui-studio/motion-preview.blade.php` | **New** — self-contained HTML page rendering the generated CSS inline, preset/speed/easing selects, all six motion-class preview elements, and a `<meta id="motion-meta">` element carrying token data-attributes for test assertions |
| `routes/web.php` | Added `/titan-ui-studio/motion-preview` route, guarded to `local` and `testing` environments only |
| `tests/DuskTestCase.php` | **New** — abstract Dusk test case wiring up ChromeDriver in headless mode |
| `tests/Browser/MotionBrowserTest.php` | **New** — 10 browser test methods (see Tests section) |
| `issue-docs/issue-284.md` | This file |

## Tests Added or Updated

All tests live in `tests/Browser/MotionBrowserTest.php`:

| Test method | Acceptance criterion |
|-------------|----------------------|
| `test_motion_preview_page_loads` | Page title and heading render without errors |
| `test_motion_tab_controls_render` | `#preset-select`, `#speed-select`, `#easing-select` are present |
| `test_all_eight_presets_present_in_select` | All 8 preset `<option>` elements exist (`none` … `scale-press`) |
| `test_changing_preset_updates_live_preview_dom` | Selecting `fade-in` updates `data-motion-preset` on `<html>` and the token display |
| `test_each_preset_selectable_without_errors` | All 8 presets are selectable and each updates `data-motion-preset` correctly |
| `test_changing_speed_updates_css_variable` | Selecting `400ms` updates `--motion-speed` CSS variable on `:root` |
| `test_changing_easing_updates_css_variable` | Selecting `spring` updates `--motion-ease` CSS variable on `:root` |
| `test_generated_css_variables_reach_rendered_dom` | `#motion-generated-css` contains `--motion-preset`, `--motion-speed`, `--motion-ease` |
| `test_generated_css_contains_reduced_motion_override` | `@media (prefers-reduced-motion: reduce)` + `animation: none !important` present in DOM |
| `test_reduced_motion_override_disables_animation_effects` | Injecting the reduced-motion override stylesheet produces `animation-name: none` on `.motion-target` |
| `test_motion_meta_element_exposes_token_data_attributes` | `#motion-meta` carries all preset slugs in `data-presets` and `data-live-preview=true` |
| `test_preview_elements_for_each_motion_class_are_present` | All six `#preview-*` elements exist in the DOM |

## Next Steps

1. **Wire real UI Studio page** — once Masterleeaus/TitanPro#216 (motion schema wired into runtime
   renderer) is merged, extend `MotionBrowserTest` to exercise the actual Filament UI Studio page
   at `/titannexus` rather than the isolated preview endpoint.
2. **CDP-level media emulation** — upgrade the reduced-motion test from CSS injection to true
   `Emulation.setEmulatedMedia` via the Chrome DevTools Protocol once a WebDriver BiDi bridge is
   available in `laravel/dusk`.
3. **Snapshot / regression baseline** — add Dusk screenshot baselines for each of the 8 presets
   so visual regressions are caught automatically.
