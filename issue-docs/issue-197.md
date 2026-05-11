# Issue 197 — [FOLLOW-UP] UI Studio: WidgetPropertyRegistry and per-widget property forms

**Follows from:** issue-docs/issue-135.md

## Summary

The UI Studio (#135) previously exposed only the column-width slider in the Layout panel
when a canvas card was selected.  This follow-up adds a `WidgetPropertyRegistry` that maps
each widget type to a flat field schema, wires it into the `UiStudio` Livewire page, and
renders an inline property editor in the right panel so studio users can configure widgets
without touching code.

---

## Files Changed

| File | Action | Purpose |
|------|--------|---------|
| `app/Filament/Pages/UiStudio/WidgetPropertyRegistry.php` | **Created** | Maps all 10 catalogue widget types to their property field schemas; provides `schema()`, `all()`, and `defaults()` static helpers. |
| `app/Filament/Pages/UiStudio.php` | **Modified** | Added `$widgetPropertyValues` state, `updateWidgetProperty()` action, updated `selectWidget()` to load registry defaults, updated `addWidget()` to initialise with defaults, updated `loadCanvasWidgets()` to hydrate saved properties, updated `publish()` to persist `properties` in the layouts table widget JSON. |
| `resources/views/filament/pages/ui-studio.blade.php` | **Modified** | Layout tab now renders a per-widget "Properties" section below the column-width slider using the field definitions from `WidgetPropertyRegistry::schema()`. Supports `text`, `textarea`, `select`, `toggle`, and `number` field types. |
| `tests/Feature/WidgetPropertyRegistryTest.php` | **Created** | Pest feature tests verifying registry completeness, `updateWidgetProperty()` write-through, `publish()` persistence, and round-trip hydration from the layouts table. |

---

## Design decisions

* **Flat field array (not Filament `Form`)** — the studio right panel is a custom Livewire
  component, not a standard Filament resource edit form.  Using a lightweight array-of-field-
  definitions (same pattern as `ComponentRegistry` tokens) avoids the Filament form lifecycle
  overhead while keeping the field type vocabulary small and readable.

* **Write-through on `updateWidgetProperty()`** — property changes are written to both the
  in-memory `$widgetPropertyValues` editor state *and* the matching `canvasWidgets` entry.
  This ensures `publish()` always operates on the latest state without requiring a separate
  "save" step.

* **`properties` key in canvas widget data** — extends the canvas widget shape to
  `{id, type, label, columns, order, properties}`.  Existing widgets that load without a
  `properties` key receive registry defaults on hydration.

* **Layouts table persistence** — the `data` object written to the `layouts.widgets` JSON
  column is now `{title, ...properties}` so legacy layout consumers (Zeus Dynamic Dashboard)
  continue to see the `title` key they already expect.

---

## Acceptance criteria status

- [x] `app/Filament/Pages/UiStudio/WidgetPropertyRegistry.php` created
- [x] Registered for all 10 currently-discoverable dashboard widget types
- [x] Layout tab renders the matching schema for the selected card
- [x] Property values persist in the layouts table widget JSON
- [x] Pest feature test that updating a widget's property is reflected after publish

---

## Next steps

* Expose a `refresh_interval` property in the UiStudio canvas preview so widgets auto-refresh
  on configurable intervals.
* Add server-side validation for each property key (e.g. reject non-numeric `max_items`).
* Consider a `WidgetPropertyRegistry::extend()` hook so module widgets can register their own
  schemas without modifying the core registry class.