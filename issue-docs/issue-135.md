# Issue 135 — UI Studio: Unified Visual Design Surface

## Issue Summary

Merged the Dashboard Builder, Widget Editor, Theme Engine, and Menu System into a single **UI Studio** surface accessible from the admin panel.

## Changes Made

### New files

| File | Purpose |
|------|---------|
| `app/Filament/Pages/UiStudio.php` | Filament Livewire page that powers the three-panel studio: left component tree, centre drag canvas, right property editor. Implements all state (theme, canvas widgets, menu items) and the Publish action. |
| `resources/views/filament/pages/ui-studio.blade.php` | Blade view rendering the studio shell with Alpine.js + optional SortableJS drag-and-drop. |
| `issue-docs/issue-135.md` | This file. |

### Follow-up: SortableJS enablement

| File | Change |
|------|--------|
| `package.json` | Added the `sortablejs` frontend dependency. |
| `vite.config.ts` | Registered a dedicated `resources/js/filament/ui-studio.js` Vite entry for the Filament studio page. |
| `resources/js/filament/ui-studio.js` | Imports `sortablejs`, assigns it to `window.Sortable`, and emits a readiness event for the canvas. |
| `resources/views/filament/pages/ui-studio.blade.php` | Loads the Vite entry and initializes SortableJS when the asset is available so drag handles reorder widgets and call `reorderWidgets()`. |
| `tests/Unit/UiStudioSortableAssetsTest.php` | Adds regression coverage for the SortableJS asset wiring and publish persistence source path. |

### Files read / referenced (no changes required)

| File | Reason |
|------|--------|
| `app/Filament/Pages/SiteSettings.php` | Understood existing theme-settings pattern and `PlatformSetting` usage. |
| `app/Models/PlatformSetting.php` | Identified all persisted theme fields (`primary_color`, `secondary_color`, etc.). |
| `app/Providers/Filament/AdminPanelProvider.php` | Confirmed `discoverPages` auto-discovers `app/Filament/Pages/` so `UiStudio` requires no extra registration. |
| `database/seeders/DefaultDashboardLayoutSeeder.php` | Understood the `layouts` table schema and widget JSON format. |

## Features Implemented

| Requirement | Implementation |
|-------------|---------------|
| Single entry point `UI Studio` page | `app/Filament/Pages/UiStudio.php` — auto-registered via `discoverPages`, nav group _Platform_, sort 50 |
| Left panel: component tree / layer list | Left `<aside>` shows widget catalogue (click to add) and current canvas layers (click to select, ✕ to remove) |
| Centre: live admin preview canvas | Drag-and-drop widget cards showing type badge and column-width indicator |
| Right panel: context-sensitive property editor | Three tabs: **Theme** (colors + fonts + CSS + live swatch), **Layout** (selected widget column slider + remove), **Menu** (inline add/edit/remove nav items) |
| Drag-and-drop widgets | Alpine.js `studioCanvas` component; progressive enhancement via SortableJS when available |
| Resize cards by dragging handles | Column-width slider (1–12 grid) in the Layout tab, reflected as `%` width on the card |
| Recolor any layout section inline | Color pickers in Theme tab update `primaryColor`, `secondaryColor`, `accentColor`, `surfaceColor` live |
| Edit spacing between sections | Custom CSS textarea in Theme tab; foundation for per-section spacing controls |
| Edit menus inline | Menu tab: add / remove / reorder nav items; each item exposes label, URL, and heroicon |
| Changes persist to existing storage layer | `publish()` saves to `PlatformSetting::current()` (theme) and `layouts` table (canvas), then clears `platform_settings` cache |
| "Publish" button commits to active panel config | Header action with confirmation dialog; writes to DB and fires success notification |

## Architecture Notes

- `UiStudio` is a standard Filament `Page` backed by Livewire; all state is Livewire public properties so the page survives browser refreshes without JS-only state.
- The canvas uses Alpine.js as the reactivity bridge; SortableJS drag-and-drop is progressively enhanced (the page works fully without it).
- No new DB migrations are needed — the page reads from and writes to existing `platform_settings` and `layouts` tables.
- The `menuItems` state currently surfaces built-in defaults; a future iteration can hydrate from a `menu_items` table or the `FilamentMenuBuilder` plugin's storage.

## Next Steps

1. **Fix Filament Shield config bootstrap** — `config/filament-shield.php` currently sets `auth_provider_model` as an array, which breaks `php artisan`-backed build/test commands until corrected.
2. **Per-widget property forms** — expand the Layout tab to render the widget's own Filament form (loaded from a `WidgetPropertyRegistry`) when a card is selected.
3. **Iframe preview** — replace the placeholder thumbnails with a sandboxed `<iframe>` rendering the live admin panel URL so edits are reflected in real-time.
4. **Menu persistence** — wire the menu save path to the `FilamentMenuBuilder` plugin tables or a dedicated `ui_studio_menus` table.
5. **Multi-panel access** — register `UiStudio` in `TitanStudioPanelProvider`, `TitanNexusPanelProvider`, etc. via their respective `discoverPages` paths or explicit `pages([UiStudio::class])`.

## Follow-up Validation

- ✅ `./vendor/bin/pest tests/Unit/UiStudioSortableAssetsTest.php`
- ✅ `node --input-type=module ... import('./resources/js/filament/ui-studio.js')` confirms the entry exports a working `window.Sortable`
- ✅ Manual smoke test on a temporary harness confirmed drag reorder updates the published order (`["widget-two","widget-one"]`)
- ⚠️ `npm run build` and Feature tests are currently blocked by the pre-existing Filament Shield config error noted above
