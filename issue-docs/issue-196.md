# Issue 196 — Component Design System Registry with Per-Component Presets

## Issue Summary

Build a component registry where each Filament UI component can be individually
styled and saved as a named preset, creating a reusable design language across all panels.

## Files Changed

### New files

| File | Purpose |
|------|---------|
| `app/Platform/Ui/ComponentRegistry.php` | Static registry mapping component keys (`stat-card`, `table`, `modal`, `sidebar`, `nav-group`, `widget-container`, `form-section`, `hero-panel`, `empty-state`) to their CSS selectors and ordered design-token lists. Each token carries a `type` (`color` / `text` / `select`), a human label, and a default value. |
| `database/migrations/2026_05_07_210000_create_titan_ui_component_overrides_table.php` | Creates the `titan_ui_component_overrides` table with columns `component`, `panel`, `organization_id`, `token_key`, `value`, `preset_name`, and a composite unique index. Active overrides have `preset_name = null`; named presets carry a `preset_name`. |
| `app/Models/TitanUiComponentOverride.php` | Eloquent model for the new table. Exposes class-level helpers: `loadTokens()`, `saveTokens()`, `savePreset()`, `applyPreset()`, `resetOverrides()`, `presetNames()`. Scopes: `active()`, `preset($name)`, `forComponent($key)`, `forPanel($panel)`. |
| `issue-docs/issue-196.md` | This file. |

### Modified files

| File | Changes |
|------|---------|
| `app/Filament/Pages/UiStudio.php` | Added imports for `TitanUiComponentOverride` and `ComponentRegistry`. Added component-registry state properties (`activeComponentKey`, `componentPanel`, `componentTokenValues`, `newPresetName`, `selectedPreset`, `availablePresets`). Added Livewire actions: `styleComponent()`, `saveComponentOverrides()`, `saveComponentPreset()`, `applyComponentPreset()`, `resetComponentOverrides()`. Added private helpers `loadOverrides()` and `fetchPresets()`. |
| `resources/views/filament/pages/ui-studio.blade.php` | Left panel: new **Design System** section listing all registered components; each row has a "Style" hover label and calls `styleComponent`. Right panel: added `components` tab to the tab strip. Added full Components tab body: panel selector, design-token editor (color picker + text for color tokens, select for option tokens, text input for text tokens), save / reset buttons, and preset management (save-as-named-preset + apply-existing-preset). |

## Features Implemented

| Requirement | Implementation |
|-------------|---------------|
| `ComponentRegistry` class mapping keys → selectors + token list | `app/Platform/Ui/ComponentRegistry.php` — 9 components, total 52 tokens |
| Design tokens per component (colors, radius, shadow, padding, typography) | Every component definition carries a typed token list with defaults |
| `titan_ui_component_overrides` DB table | Migration `2026_05_07_210000_create_titan_ui_component_overrides_table.php` |
| UI Studio component list with "Style" button | Left panel "Design System" section; clicking a row calls `styleComponent()` |
| Clicking "Style" opens the Visual Inspector pre-selected | `styleComponent()` sets `activeComponentKey`, switches `activeTab` to `components`, loads saved overrides merged with defaults |
| Preset system: save current styling as a named preset | `saveComponentPreset()` + "Save as preset" UI in Components tab |
| Apply preset to other panels / tenants | `applyComponentPreset()` + panel-selector dropdown + "Apply" button |
| "Reset to theme defaults" clears component overrides | `resetComponentOverrides()` deletes DB rows and resets live state; confirmation dialog on button |

## Architecture Notes

- `ComponentRegistry` is a pure static class with no dependencies — safe to call from Blade, controllers, and artisan commands.
- The `titan_ui_component_overrides` table uses a unique index on `(component, panel, organization_id, token_key, preset_name)` allowing distinct active overrides and multiple named presets for the same token.
- All DB access is guarded by `Schema::hasTable('titan_ui_component_overrides')` so the page degrades gracefully before migrations are run.
- Token values are stored as plain strings; the registry's `selector` + `token_key` are intended to be compiled into `<style>` injections in a future CSS-generation pass.

## Next Steps

1. **CSS generation** — add a `ComponentOverrideCssService` that reads active overrides and generates a `<style>` block scoped to each component's selector, injected via a Filament `renderHook`.
2. **Organization scoping** — `organization_id` is present in the table; a future iteration can expose a tenant selector in the Components tab so resellers can give each tenant a distinct look.
3. **Import / export presets** — export all presets for a component as JSON; import via file upload to share design systems across installations.
4. **PHP 8.4 test run** — once the CI environment is updated to PHP ≥ 8.4, run `./vendor/bin/pest` to confirm no regressions (current sandbox has PHP 8.3 and no `vendor/`).
5. **Panel registration** — the UiStudio page is auto-discovered in `app/Filament/Pages/`; verify it appears in all desired panels by checking their `discoverPages` paths.
