# Issue #194 — SSR Visual UI Inspector Overrides

**Follows from:** issue-docs/issue-193.md (Visual UI Inspector)

## Summary

Pre-renders `UiOverride::allForOrg($orgId)` as an inline `<style>` block in
every Filament panel's `<head>` via a `panels::head.end` render hook.  This
eliminates the flash of unstyled content that occurred when overrides were
applied client-side by Alpine.js after the first paint.

---

## Files Changed

### New files

| File | Purpose |
|------|---------|
| `app/Services/UiOverrideCssRenderer.php` | Service that converts `UiOverride::allForOrg($orgId)` into a ready-to-embed CSS string.  Uses `ComponentRegistry` selectors for known component keys and falls back to `[data-ui-key="…"]` selectors for arbitrary Alpine-inspector keys.  Expands the virtual `--gradient`, `--glass`, and `--animation` pseudo-properties to their real CSS equivalents. |
| `resources/views/filament/ui-override-ssr.blade.php` | Blade partial rendered in `panels::head.end`.  Resolves the authenticated user's `organization_id`, calls `UiOverrideCssRenderer::render()`, and emits `<style id="titan-ui-override-ssr" data-ssr="1">…</style>` when there are overrides to apply. |
| `tests/Feature/UiOverrideSsrTest.php` | Pest feature tests covering: empty-org returns empty string, ComponentRegistry selector usage, `[data-ui-key]` fallback, `--gradient`/`--glass`/`--animation` expansion, empty-value skipping, org-isolation, and `buildDeclarations` direct mapping. |

### Modified files

| File | Change |
|------|--------|
| `app/Providers/Filament/Concerns/RegistersFilamentPlugins.php` | Added `uiOverrideSsrHook()` private method returning the `panels::head.end` render hook for the SSR Blade partial. |
| `app/Providers/Filament/TitanProPanelProvider.php` | Added `->renderHook(...$this->uiOverrideSsrHook())` before the inspector hook. |
| `app/Providers/Filament/GroundZeroPanelProvider.php` | Same. |
| `app/Providers/Filament/TitanGoPanelProvider.php` | Same. |
| `app/Providers/Filament/TitanNexusPanelProvider.php` | Same. |
| `app/Providers/Filament/TitanQuotesPanelProvider.php` | Same. |
| `app/Providers/Filament/TitanSoloPanelProvider.php` | Same. |
| `app/Providers/Filament/TitanStudioPanelProvider.php` | Same. |
| `app/Providers/Filament/ZeroFussPanelProvider.php` | Same. |
| `app/Providers/Filament/ZeroPayPanelProvider.php` | Same. |
| `public/js/titan/ui-inspector.js` | Changed the `DOMContentLoaded` listener to skip `applyAllStoredOverrides()` when `#titan-ui-override-ssr` is already present in the DOM, preventing redundant double-application on first paint.  Livewire SPA navigation continues to re-apply from localStorage as before. |

---

## Acceptance criteria status

| Criterion | Status |
|-----------|--------|
| Service / Blade partial generates `<style>` from `UiOverride::allForOrg` | ✅ `UiOverrideCssRenderer` + `ui-override-ssr.blade.php` |
| Injected via `panels::head.end` render hook in `RegistersFilamentPlugins` | ✅ `uiOverrideSsrHook()` added; all 9 panel providers updated |
| CSS selectors match those used by `ComponentRegistry` | ✅ `ComponentRegistry::get($key)['selector']` used for registry keys |
| Alpine inspector continues to overlay live edits (no double-apply) | ✅ DOMContentLoaded skipped when SSR block present; Livewire nav still works |
| Pest test asserts `<style>` block contains saved override for a test org | ✅ `tests/Feature/UiOverrideSsrTest.php` (8 test cases) |

---

## CSS generation logic

For each `UiOverride` row:
1. If `component_key` exists in `ComponentRegistry` → use `ComponentRegistry::get($key)['selector']` (e.g. `.fi-wi-stats-overview-stat, .fi-stat-card`)
2. Otherwise → use `[data-ui-key="<key>"]` selector so Alpine-inspector overrides are also pre-rendered
3. `properties` map is expanded: `--gradient` → `background`, `--glass` → `backdrop-filter` + `-webkit-backdrop-filter` + `background-color`, `--animation` → `transition` (and `animation` for `pulse`).  All other keys are emitted as-is.

---

## Next Steps

1. **Run migration** — Ensure `php artisan migrate` has been run to create the `ui_overrides` table.
2. **Cache** — Consider caching `UiOverrideCssRenderer::render($orgId)` per-org with a short TTL (e.g. 60 s) and invalidating on `UiOverride::saved` / `deleted` model events for high-traffic panels.
3. **CSP** — If a Content-Security-Policy header is in use, the inline `<style>` block will need a nonce added to the Blade partial.
4. **Export/import** — Allow exporting the full override set as a JSON theme file (from issue-193 next steps).
