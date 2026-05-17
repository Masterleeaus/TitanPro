## Issue Summary

Follow-up to issue-284. Upgraded `MotionBrowserTest::test_reduced_motion_override_disables_animation_effects`
from a CSS-injection workaround to a genuine CDP `Emulation.setEmulatedMedia` call. The previous
implementation injected an override `<style>` element to simulate `prefers-reduced-motion: reduce`;
this validated the CSS rule text but did not exercise the real browser media-query evaluation path.
The new implementation uses the Chrome DevTools Protocol to set the media feature at the browser
engine level, so the `@media (prefers-reduced-motion: reduce)` block in the generated CSS is
evaluated by the browser itself.

## Root Cause

`laravel/dusk ^8.6` ships with `php-webdriver/webdriver ^1.15.2`, which exposes
`RemoteWebDriver::executeCdpCommand()` (added upstream in v1.11.0). This method forwards CDP
commands directly to the browser's DevTools endpoint via ChromeDriver's `/session/{id}/goog/cdp/execute`
route. No additional packages or WebDriver BiDi bridge are required — CDP command support is already
available in the locked dependency.

## Changes Made

| File | Change |
|------|--------|
| `tests/DuskTestCase.php` | Added `withReducedMotion(Browser $browser): void` helper that calls `Emulation.setEmulatedMedia` with `prefers-reduced-motion: reduce`; added `resetMotionEmulation(Browser $browser): void` companion that clears the emulation after the test |
| `tests/Browser/MotionBrowserTest.php` | Replaced CSS-injection approach in `test_reduced_motion_override_disables_animation_effects` with `$this->withReducedMotion($browser)` + `$this->resetMotionEmulation($browser)`; updated docblock to reflect the real CDP path |
| `.github/workflows/motion-browser.yml` | Added `tests/DuskTestCase.php` to `paths` filter on both `push` and `pull_request` triggers so CI reruns when the base test case changes |
| `issue-docs/issue-287.md` | This file |

## Fixes Applied

1. **`withReducedMotion(Browser $browser): void`** (new method in `DuskTestCase`) — Sends
   `Emulation.setEmulatedMedia` with `features: [{ name: 'prefers-reduced-motion', value: 'reduce' }]`
   to ChromeDriver. This activates the OS-level media feature inside the headless Chrome session,
   causing all `@media (prefers-reduced-motion: reduce)` blocks to be evaluated as if the user's
   OS accessibility preference were enabled.

2. **`resetMotionEmulation(Browser $browser): void`** (new method in `DuskTestCase`) — Sends
   `Emulation.setEmulatedMedia` with an empty `features` array to clear any active emulation and
   prevent state leaking into subsequent test cases.

3. **Updated test** — `test_reduced_motion_override_disables_animation_effects` now:
   - Visits the motion-preview page and selects `fade-in` (which would normally apply a CSS animation)
   - Calls `$this->withReducedMotion($browser)` to activate real CDP media emulation
   - Asserts `getComputedStyle(#preview-target).animationName === 'none'` (the `@media` rule fires)
   - Calls `$this->resetMotionEmulation($browser)` to restore the default state

## Acceptance Criteria

| Criterion | Status |
|-----------|--------|
| Determine Dusk CDP support | ✅ `laravel/dusk ^8.6` + `php-webdriver/webdriver ^1.15.2` provides `executeCdpCommand` — no upgrade required |
| `DuskTestCase::withReducedMotion()` calls `Emulation.setEmulatedMedia` | ✅ Implemented in `tests/DuskTestCase.php` |
| `test_reduced_motion_override_disables_animation_effects` uses real CDP emulation | ✅ Replaced CSS injection with `withReducedMotion()` / `resetMotionEmulation()` |
| Test asserts `animation-name: none` on `.motion-target` | ✅ Same assertion as before — `assertSame('none', $animationName[0])` |
| CI workflow runs the upgraded test | ✅ `.github/workflows/motion-browser.yml` now tracks `tests/DuskTestCase.php` in its path filter |

## Next Steps

1. **Snapshot / regression baseline** — add Dusk screenshot baselines for each of the 8 presets so
   visual regressions are caught automatically.
2. **Wire real UI Studio page** — once the motion schema is wired into the Filament runtime renderer,
   extend `MotionBrowserTest` to exercise the actual `/titannexus` panel rather than the isolated
   preview endpoint.
3. **Extend CDP helpers** — consider exposing `withColorScheme(Browser, 'dark'|'light')` and
   `withForcedColors(Browser)` using the same `Emulation.setEmulatedMedia` pattern.
