## Issue Summary

Added a visual grid editor to the platform settings UI so operators can resize sidebar width, content width, widget spans, widget heights, and row spacing directly from a live preview without hand-editing CSS.

## Root Cause

The existing platform settings page only exposed basic branding controls and a simple preview. Layout tokens were not surfaced visually, no drag-based editing workflow existed, and persisted token values were not being saved through the current `custom_css` storage path.

## Changes Made

- updated `/home/runner/work/TitanPro/TitanPro/resources/js/pages/Platform/Settings.vue`
  - added the grid overlay editor, snap-to-grid controls, drag handles, undo/redo, and reset behavior
  - wired token generation into the existing form submit flow using `custom_css`
- added `/home/runner/work/TitanPro/TitanPro/resources/js/pages/Platform/layoutTokens.ts`
  - centralised layout token defaults, parsing, sanitising, snapping, and CSS block generation
- updated `/home/runner/work/TitanPro/TitanPro/app/Http/Controllers/Platform/SettingsController.php`
  - accepted and persisted `custom_css`
  - returned stored token CSS to the page payload
  - fixed cache invalidation to clear `platform_settings`
- updated `/home/runner/work/TitanPro/TitanPro/resources/js/layouts/PlatformLayout.vue`
  - applied persisted sidebar width and content max width tokens to the platform shell

## Tests Added or Updated

- no automated tests were added because the repository does not currently include frontend component/unit test infrastructure for Vue drag interactions
- validated the edited frontend files with targeted ESLint runs
- attempted baseline/build validation, but PHP-dependent commands still fail in this environment because `vendor/` is unavailable and the repo requires PHP 8.4 dependencies

## Next Steps

- add dedicated frontend component tests once Vue test tooling is available
- extend the persisted layout tokens to other runtime shells beyond the platform layout if broader theme-wide application is required
- validate the drag interactions against a full Laravel runtime with vendor dependencies installed
