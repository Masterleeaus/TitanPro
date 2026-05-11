# Issue 197 — UI Studio: persist menu items via fmm_* tables

## Summary

Follow-up to issue-135 (UI Studio) and issue-155 (Menu Manager schema hardening).
The Menu tab in UI Studio previously surfaced default nav items but lost all
edits on page refresh because nothing was written to a durable store.

This issue wires the menu save / load path to the existing
`FilamentMenuManager` (`fmm_*`) tables so that add, remove, and reorder
operations are persisted per-organisation.

---

## Root Cause

`UiStudio::loadMenuItems()` only checked the `organization_brandings.menu_items`
JSON column, which was only populated if a user clicked **Publish**.  Even then,
reading only worked because both load and save used the same JSON field.  There
was no integration with the `fmm_*` tables that are the canonical menu store for
the platform.

---

## Changes Made

### `app/Filament/Pages/UiStudio.php`

| Method | Change |
|--------|--------|
| `loadMenuItems()` | Rewritten.  Priority order: (1) `fmm_menus` / `fmm_menu_items` row keyed by `ui-studio-org-{orgId}` slug, (2) `organization_brandings.menu_items` JSON (legacy fallback), (3) hard-coded defaults. |
| `persistMenuItemsToFmm(int $orgId)` | **New private helper.** Ensures a `fmm_menu_locations` row with handle `ui-studio` exists, then upserts a per-org `fmm_menus` row (`slug = ui-studio-org-{orgId}`), then does a delete-and-re-insert of `fmm_menu_items` to reflect the current ordered state. |
| `publish()` | Calls `$this->persistMenuItemsToFmm($orgId)` immediately after saving the `OrganizationBranding` row so both stores are always in sync. |

### `tests/Feature/UiStudioMenuPersistenceTest.php`

New Pest feature test covering:

1. **Table existence** — `fmm_menu_locations`, `fmm_menus`, `fmm_menu_items` all exist.
2. **Seed and ordered retrieval** — items inserted in reverse order are returned in `order ASC`.
3. **Replace (delete + re-insert)** — old rows are removed and new rows appear after a publish cycle.
4. **Disabled items excluded** — `enabled = false` rows are filtered out by the query.
5. **Tenant isolation** — two organisations each get their own slug and their items don't bleed across.

---

## Acceptance Criteria Status

| Criterion | Status |
|-----------|--------|
| Menu state persists per org/panel | ✅ fmm row keyed by `ui-studio-org-{orgId}` |
| Existing nav defaults imported on first edit | ✅ defaults returned until a published fmm row exists |
| Add / remove / reorder reflected after refresh | ✅ `persistMenuItemsToFmm` deletes + re-inserts on every publish |
| Pest feature test for persistence | ✅ `tests/Feature/UiStudioMenuPersistenceTest.php` (5 tests) |
| Compatible with Menu Manager schema (issue-155) | ✅ uses `fmm_` prefix from `config('filament-menu-manager.table_prefix')` |

---

## Files Changed

- `app/Filament/Pages/UiStudio.php`
- `tests/Feature/UiStudioMenuPersistenceTest.php` _(new)_
- `issue-docs/issue-197.md` _(this file)_

---

## Next Steps

1. **Livewire Publish-cycle integration test** — once the PHP 8.4 environment
   constraint is resolved the test suite could add a `Livewire::test(UiStudio::class)`
   test that calls `addMenuItem()` + `publish()` and then re-mounts the component
   to verify `$menuItems` are hydrated from the fmm tables.
2. **Import from existing fmm menus** — the Studio could offer a picker so operators
   can clone an already-configured Filament Menu Manager menu as their starting point.
3. **Multi-panel scoping** — if UiStudio is registered on multiple panels the slug
   could be extended to `ui-studio-panel-{panel}-org-{orgId}` for per-panel menus.
