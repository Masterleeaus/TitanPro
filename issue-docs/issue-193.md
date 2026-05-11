# Issue #193 — Visual UI Inspector

## Summary

Implemented the full production-grade Visual UI Inspector for Filament panels.
Users can hover over any Filament component to see a highlight overlay, click to
select it, and edit CSS properties live in a floating sidebar — without a page
reload.

---

## Files Created

| File | Purpose |
|------|---------|
| `database/migrations/2026_05_07_210000_create_ui_overrides_table.php` | Schema for `ui_overrides` — stores per-component CSS property overrides keyed by `(component_key, organization_id)` |
| `app/Models/UiOverride.php` | Eloquent model; static helpers: `upsertForComponent`, `allForOrg`, `resetComponent` |
| `app/Http/Controllers/UiInspectorController.php` | API controller: `index`, `upsert`, `reset`, `resetAll` |
| `public/js/titan/ui-inspector.js` | Alpine.js component providing hover-select, click-to-edit, live CSS injection, and localStorage persistence |
| `resources/views/filament/ui-inspector.blade.php` | HTML for the floating toggle button, hover overlay, tooltip, and property sidebar |

## Files Modified

| File | Change |
|------|--------|
| `routes/web.php` | Added `GET/POST/DELETE /titan/ui-inspector/overrides` routes guarded by `auth + role:super_admin|admin|owner` |
| `app/Providers/Filament/Concerns/RegistersFilamentPlugins.php` | Added `uiInspectorHook()` helper — returns the `panels::body.end` render hook definition |
| `app/Providers/Filament/TitanProPanelProvider.php` | Calls `->renderHook(...$this->uiInspectorHook())` |
| `app/Providers/Filament/GroundZeroPanelProvider.php` | Same |
| `app/Providers/Filament/TitanGoPanelProvider.php` | Same |
| `app/Providers/Filament/TitanNexusPanelProvider.php` | Same |
| `app/Providers/Filament/TitanQuotesPanelProvider.php` | Same |
| `app/Providers/Filament/TitanSoloPanelProvider.php` | Same |
| `app/Providers/Filament/TitanStudioPanelProvider.php` | Same |
| `app/Providers/Filament/ZeroFussPanelProvider.php` | Same |
| `app/Providers/Filament/ZeroPayPanelProvider.php` | Same |

---

## Feature Coverage

| Requirement | Status |
|-------------|--------|
| Hover-select: dashed highlight border + component name tooltip | ✅ |
| Click-to-edit: clicking opens property sidebar | ✅ |
| Padding / margin with visual spacing preview | ✅ |
| Border radius | ✅ |
| Box shadow — presets (none/sm/md/lg/xl/inner) + custom text field | ✅ |
| Background color — colour picker + hex/rgb input | ✅ |
| Text color — colour picker + hex/rgb input | ✅ |
| Font size slider (0.5–3rem) | ✅ |
| Font weight selector (300–900) | ✅ |
| Gradient builder — linear/radial, from/to colours, angle | ✅ |
| Glassmorphism toggle — backdrop-blur slider | ✅ |
| Animation preset selector (none/fadeIn/slideUp/scaleIn/bounceIn/pulse) | ✅ |
| Changes applied instantly via CSS injection (no reload) | ✅ |
| "Reset component" reverts to theme defaults | ✅ |
| Overrides persisted to `ui_overrides` table via API | ✅ |
| Overrides also cached in `localStorage` for instant re-application | ✅ |
| Stored overrides re-applied after Livewire navigation | ✅ |
| Inspector toggled on/off via floating button (bottom-right) | ✅ |
| `body.titan-inspector-active` class drives cursor changes | ✅ |

---

## Architecture

```
Blade render hook (panels::body.end)
  └─ resources/views/filament/ui-inspector.blade.php
       ├─ Alpine.js x-data="titanUiInspector"
       │    ├─ Hover: mousemove → nearestComponent() → overlayStyle
       │    ├─ Click: stopPropagation → _loadComponentProps → sidebarOpen=true
       │    ├─ Property changes: applyProps(el, props) via inline style API
       │    └─ Save: POST /titan/ui-inspector/overrides
       └─ public/js/titan/ui-inspector.js  (Alpine data factory)

UiInspectorController
  ├─ index   → UiOverride::allForOrg($orgId)
  ├─ upsert  → UiOverride::upsertForComponent(...)
  ├─ reset   → UiOverride::resetComponent($key, $orgId)
  └─ resetAll→ UiOverride::where('organization_id', …)->delete()
```

---

## Next Steps

1. **Run migration** — `php artisan migrate` to create `ui_overrides`.
2. **Scoped Filament identifiers** — Consider adding `data-ui-key` attributes to
   key Filament components (widgets, cards, tables) so the element key is stable
   across Livewire re-renders.
3. **Permission refinement** — Currently open to `super_admin|admin|owner`; add a
   dedicated `ui-inspector.manage` gate/permission for finer control.
4. **Server-side override injection** — Pre-render saved overrides as `<style>` in
   the Blade layout (using `UiOverride::allForOrg`) so overrides are visible before
   Alpine.js boots, eliminating any flash.
5. **Toasts and inline feedback** — Add success/error toasts for import/export actions.

---

## Follow-up (Export / Import override set as JSON)

### Files Changed

| File | Change |
|------|--------|
| `app/Http/Controllers/UiInspectorController.php` | Added `export` and `import` actions, org-scoped JSON download filename, and import payload validation for allowed keys + safe CSS values |
| `routes/web.php` | Added `GET /titan/ui-inspector/export` and `POST /titan/ui-inspector/import` routes |
| `public/js/titan/ui-inspector.js` | Added export/download flow and JSON import flow in Alpine component |
| `resources/views/filament/ui-inspector.blade.php` | Added floating panel buttons: **Export overrides** and **Import overrides** |
| `tests/Feature/UiInspectorImportExportTest.php` | Added Pest round-trip test (export → wipe → import → restore) and invalid import validation tests |

### Fixes Applied

1. Implemented org-scoped override export as downloadable JSON with filename format:
   `ui-overrides-{org-name-slug}-{YYYY-MM-DD}.json`.
2. Implemented import endpoint that accepts exported JSON shape and upserts each component override for the current org.
3. Added structure validation to reject unknown CSS property keys.
4. Added CSS value safety validation to reject dangerous/invalid values (e.g. `url(...)`, `javascript:`, braces/semicolon payloads).
5. Added inspector floating-panel import/export controls wired to the new backend endpoints.
6. Added feature tests proving round-trip data integrity and invalid import rejection.

### Next Steps

1. Add UI notifications so users can see import/export success or validation errors without opening browser dev tools.
2. Optionally support multipart file upload (`file`) in the UI path if large payload handling is needed later.
