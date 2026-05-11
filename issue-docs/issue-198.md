# Issue 198 — Responsive UI Designer Preview Modes and Overrides

## Files Changed

| File | Changes |
|------|---------|
| `app/Filament/Pages/UiStudio.php` | Added responsive preview state (`previewMode`, `responsiveBreakpoint`, `responsiveTokenOverrides`), preview helpers (mode/viewport/url), per-table column visibility updates, responsive override normalization/defaults, and persistence of responsive overrides into `platform_settings.theme_snapshots.ui_studio_responsive_overrides`. |
| `resources/views/filament/pages/ui-studio.blade.php` | Added device-frame switcher in the live-preview toolbar, iframe-based preview frame that resizes by selected viewport mode, responsive preview sandbox using heading/card token overrides, responsive override editor controls in the Layout tab, and configurable table column hide controls for mobile/tablet on table widgets. |
| `tests/Feature/UiStudioResponsivePreviewTest.php` | Added focused tests for required preview modes/viewports, preview mode viewport switching, and per-table hidden-column configuration behavior. |
| `issue-docs/issue-198.md` | This issue summary document. |

## Fixes Applied

1. Added five preview modes: Desktop (1440), Tablet (1024), Mobile (390), Collapsed sidebar (1440), and Customer portal (390).
2. Added a live preview iframe that is constrained to the selected viewport width.
3. Implemented per-breakpoint token overrides (`sidebar_width`, `heading_scale`, `card_padding`) with Layout-tab controls.
4. Wired typography/card preview to the active breakpoint override values.
5. Added table-column visibility controls per table widget for mobile/tablet breakpoints and used those settings in the preview table.
6. Stored responsive overrides separately from base theme tokens in `platform_settings.theme_snapshots.ui_studio_responsive_overrides`.

## Next Steps

1. Apply responsive token overrides to the real panel runtime CSS (not only the studio preview shell).
2. Expand table-column controls from sample columns to resource-specific, schema-driven column lists.
3. Validate and run the new Feature test in CI/runtime with PHP 8.4 + installed Composer dependencies.
