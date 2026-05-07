## Issue Summary

Implemented a focused accessibility engine for the active platform theme, including audit reporting, one-click token fixes, visible focus styling, reduced-motion support, and a new UI Studio accessibility surface.

## Root Cause

The repository had basic theme settings and a theme manager, but no first-party accessibility audit service, no stored accessibility reports, and no shared token-driven accessibility CSS for focus visibility, font scaling, or motion preferences.

## Changes Made

- Added `App\Services\Accessibility\AccessibilityAudit` to audit theme tokens, auto-fix contrast/font issues, and generate shared accessibility CSS.
- Added `App\Models\TitanAccessibilityReport` plus `titan_accessibility_reports` persistence.
- Extended `PlatformSetting` with accessibility theme tokens and dismissal state.
- Added a new Filament `UI Studio` page with an `Accessibility` tab-like surface, report history, dismiss/restore actions, and an auto-fix action.
- Injected shared accessibility CSS into app and Filament panel rendering.
- Added database migrations for accessibility token fields and stored audit reports.

## Tests Added or Updated

- Added `tests/Feature/Admin/UiStudioAccessibilityTest.php` covering:
  - report persistence for failing audits
  - auto-fix behavior
  - UI Studio accessibility page access

## Next Steps

- Run the new feature tests once Composer dependencies are installed under PHP 8.4.
- Capture a full in-browser verification of the new UI Studio accessibility page after the Laravel app can boot locally.
- Consider expanding the audit engine to inspect theme ZIP manifests directly when package source/vendor dependencies are available in the environment.
