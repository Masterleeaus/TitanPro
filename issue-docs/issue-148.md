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

---

## Follow-up: platform-wide runtime shell token application

### Active runtime shells identified for token consumption

- Inertia shells
  - `/home/runner/work/TitanPro/TitanPro/resources/js/layouts/PlatformLayout.vue` (already consuming sidebar/content tokens from issue #148)
  - `/home/runner/work/TitanPro/TitanPro/resources/js/layouts/app/AppSidebarLayout.vue` + sidebar primitives under `/home/runner/work/TitanPro/TitanPro/resources/js/components/ui/sidebar/`
  - `/home/runner/work/TitanPro/TitanPro/resources/js/layouts/OwnerLayout.vue`
  - `/home/runner/work/TitanPro/TitanPro/resources/js/layouts/TechnicianLayout.vue` (content shell; no desktop sidebar region)
- Filament panel shells (registered in `/home/runner/work/TitanPro/TitanPro/bootstrap/providers.php`)
  - TitanPro, GroundZero, TitanQuotes, ZeroPay, TitanGo, ZeroFuss, TitanSolo, TitanStudio, TitanNexus (all consume shared `filament.ui-inspector` render hook from `/home/runner/work/TitanPro/TitanPro/app/Providers/Filament/Concerns/RegistersFilamentPlugins.php`)

### Files changed in this follow-up

- updated `/home/runner/work/TitanPro/TitanPro/resources/js/components/ui/sidebar/SidebarProvider.vue`
  - bridged app sidebar width to persisted token via `--runtime-shell-sidebar-width: var(--sidebar-width, 16rem)`
- updated `/home/runner/work/TitanPro/TitanPro/resources/js/components/ui/sidebar/Sidebar.vue`
  - switched sidebar width usage to `--runtime-shell-sidebar-width` for desktop/mobile shells
- updated `/home/runner/work/TitanPro/TitanPro/resources/js/layouts/app/AppSidebarLayout.vue`
  - constrained runtime content area with `maxWidth: var(--content-max-width, 80rem)`
- updated `/home/runner/work/TitanPro/TitanPro/resources/js/layouts/OwnerLayout.vue`
  - applied sidebar width token and content max-width token
- updated `/home/runner/work/TitanPro/TitanPro/resources/js/layouts/TechnicianLayout.vue`
  - applied content max-width token to technician shell content container
- updated `/home/runner/work/TitanPro/TitanPro/resources/views/filament/ui-inspector.blade.php`
  - injected persisted `custom_css` into Filament panel runtime shell path
  - applied token-driven Filament sidebar/content shell sizing rules
- updated `/home/runner/work/TitanPro/TitanPro/app/Http/Controllers/Platform/SettingsController.php`
  - clears `platform_settings_custom_css` cache key when platform settings are saved
- updated `/home/runner/work/TitanPro/TitanPro/app/Filament/Pages/SiteSettings.php`
  - clears `platform_settings_custom_css` cache key when site settings are saved/reset
- updated `/home/runner/work/TitanPro/TitanPro/app/Filament/Pages/UiStudio.php`
  - clears `platform_settings_custom_css` cache key after saving custom CSS

### Next steps

- verify final Filament class selectors against live panel markup in browser QA (desktop + mobile)
- add dedicated visual regression coverage for shell width tokens when frontend test tooling is available
