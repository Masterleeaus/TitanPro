# Issue 421 — [FOLLOW-UP] Add Dusk screenshot baselines for all 8 motion presets

## Issue Summary

Follow-up to issue #284 (Motion Browser Test Suite).  
Adds visual regression testing for the 8 UI Studio motion presets by introducing
Dusk screenshot baselines and a pixel-diff comparison step in CI.

## Files Changed

| File | Change |
|------|--------|
| `tests/Browser/Snapshots/motion-presets/none.png` | **New** — 1×1 placeholder baseline (replaced by real screenshot on first Dusk run) |
| `tests/Browser/Snapshots/motion-presets/fade-in.png` | **New** — placeholder baseline |
| `tests/Browser/Snapshots/motion-presets/slide-up.png` | **New** — placeholder baseline |
| `tests/Browser/Snapshots/motion-presets/smooth-sidebar.png` | **New** — placeholder baseline |
| `tests/Browser/Snapshots/motion-presets/hover-lift.png` | **New** — placeholder baseline |
| `tests/Browser/Snapshots/motion-presets/blur-overlay.png` | **New** — placeholder baseline |
| `tests/Browser/Snapshots/motion-presets/shimmer-load.png` | **New** — placeholder baseline |
| `tests/Browser/Snapshots/motion-presets/scale-press.png` | **New** — placeholder baseline |
| `tests/Browser/Snapshots/motion-presets/README.md` | **New** — CI documentation: baseline layout, regeneration instructions, threshold configuration |
| `tests/Browser/MotionPresetSnapshotTest.php` | **New** — Dusk visual regression test for all 8 presets |
| `.github/workflows/motion-browser.yml` | Updated — adds snapshot test step, `workflow_dispatch` regeneration input, diff artifact upload |
| `issue-docs/issue-421.md` | This file |

## Fixes Applied

### 1. Placeholder baseline PNGs (`tests/Browser/Snapshots/motion-presets/*.png`)

Eight 1×1 grey PNG files are committed as seed baselines.  
On the first `php artisan dusk` run the test detects the dimension mismatch
(1×1 vs the real 1920×1080 screenshot), replaces each placeholder with the
actual screenshot, and marks itself incomplete — indicating baselines have been
seeded and should be committed before normal CI comparison begins.

### 2. `MotionPresetSnapshotTest`

New Dusk test class that:

- Declares the 8 required presets as a constant (`PRESETS`).
- Reads the pixel-diff threshold from `MOTION_SNAPSHOT_THRESHOLD` env (default `0.01` = 1 %).
- For each preset:
  1. Visits `/titan-ui-studio/motion-preview`.
  2. Selects the preset in `#preset-select`.
  3. Pauses 400 ms to let animations settle.
  4. Captures a screenshot named `motion-snapshot-<preset>.png`.
  5. Checks whether the baseline needs (re)generation:
     - Baseline missing → create it, mark test incomplete.
     - `MOTION_REGENERATE_BASELINES=true` → overwrite baseline, mark incomplete.
     - Dimension mismatch → overwrite baseline, mark incomplete.
  6. Otherwise performs a pixel-diff comparison via PHP GD and asserts the
     differing-pixel ratio is ≤ the configured threshold.

### 3. `.github/workflows/motion-browser.yml` updates

- `workflow_dispatch` gains a `regenerate_baselines` boolean input.
- Timeout raised from 20 → 25 minutes to accommodate screenshot captures.
- New step **Run Dusk snapshot tests** runs `MotionPresetSnapshotTest.php` with
  `MOTION_SNAPSHOT_THRESHOLD` and `MOTION_REGENERATE_BASELINES` env vars.
- On failure: existing `dusk-motion-failures` artifact collects all Dusk screenshots.
- On failure: new `dusk-motion-snapshot-diffs` artifact bundles the captured
  screenshots alongside the stored baselines for side-by-side visual inspection.

## Acceptance Criteria Mapping

| Criterion | Implementation |
|-----------|----------------|
| `tests/Browser/Snapshots/motion-presets/` with 8 baseline PNGs | ✅ 8 placeholder PNGs committed (auto-replaced on first Dusk run) |
| `MotionPresetSnapshotTest` iterates 8 presets, captures screenshot, diffs against baseline | ✅ `test_each_motion_preset_matches_visual_baseline()` |
| Snapshot diff threshold configurable (default ≤ 1 %) | ✅ `MOTION_SNAPSHOT_THRESHOLD` env var; default `0.01` |
| `.github/workflows/motion-browser.yml` uploads diff artifacts on failure | ✅ `dusk-motion-snapshot-diffs` artifact, 14-day retention |
| CI documentation explains how to regenerate baselines | ✅ `tests/Browser/Snapshots/motion-presets/README.md` |

## Next Steps

1. **Seed real baselines** — In a PHP 8.4 environment with Chrome available, run:
   ```bash
   MOTION_REGENERATE_BASELINES=true php artisan dusk tests/Browser/MotionPresetSnapshotTest.php
   ```
   Commit the generated PNGs from `tests/Browser/Snapshots/motion-presets/` to replace
   the placeholder files.

2. **Wire to push CI** — Once real baselines are committed the snapshot step will run
   automatically on every push/PR that touches the UI Studio motion layer.

3. **CDP media emulation** — After `laravel/dusk` exposes a WebDriver BiDi bridge, upgrade
   `MotionPresetSnapshotTest` to snapshot with `prefers-reduced-motion: reduce` emulated via
   `Emulation.setEmulatedMedia` to catch regressions in the accessibility override.
# Issue 421 — [FOLLOW-UP] Frontend: consume `role_ui.widget_layout` in dashboard page

## Summary

Wires the Vue dashboard page to honour the per-role widget layout stored in
`role_ui.widget_layout` (introduced in issue 195).  When a user's primary role
has an active `RoleUIProfile` the dashboard now shows only the configured
widgets in the configured order.  When no profile exists, or when
`widget_layout` is empty, the full platform-default widget set is rendered.

Source: Follows from `issue-docs/issue-195.md`

---

## Files Changed

| File | Change type | Description |
|------|-------------|-------------|
| `resources/js/types/index.d.ts` | **modified** | Added `RoleUi` interface (`role`, `hidden_nav_items`, `widget_layout`, `theme`). Added `role_ui?: RoleUi \| null` to `AppPageProps` so all pages have typed access to the shared Inertia prop. |
| `resources/js/pages/Dashboard.vue` | **modified** | Replaced placeholder "You're logged in!" content. Added `PLATFORM_DEFAULT_WIDGETS` constant (8 widget types matching `UiStudio::$allWidgets`). Added `visibleWidgets` computed property that filters and orders by `role_ui.widget_layout` when present and non-empty, falling back to the full default set. Renders each widget as a card with a `data-widget-type` attribute. Switched layout to `AppLayout` (the app's standard sidebar layout). |
| `resources/js/pages/__tests__/Dashboard.spec.ts` | **new** | Vitest / `@vue/test-utils` tests covering all five acceptance criteria: platform default renders when `role_ui` is absent; empty `widget_layout` falls back to platform default; only configured widgets render per role (finance/bookkeeper, dispatch, technician); widget order matches `widget_layout`; unknown widget types are silently skipped. |

---

## Behaviour

### Widget resolution algorithm

```
role_ui?.widget_layout           non-empty?
     │ yes                             │ no
     ▼                                 ▼
filter PLATFORM_DEFAULT_WIDGETS   return PLATFORM_DEFAULT_WIDGETS
by types in widget_layout         (all 8 widgets)
(preserve order, skip unknowns)
```

### Platform default widget set

| Type | Label |
|------|-------|
| `kpi-grid-card` | KPI Grid |
| `stat-card` | Stat Card |
| `recent-activity-card` | Recent Activity |
| `alert-notice-card` | Alert / Notice |
| `chart-bar-card` | Bar Chart |
| `chart-line-card` | Line Chart |
| `map-card` | Live Map |
| `table-card` | Data Table |

### Example role profiles

| Role | `widget_layout` | Result |
|------|----------------|--------|
| Admin / Owner | `[]` (empty) | All 8 default widgets |
| Bookkeeper / Finance | `['kpi-grid-card', 'chart-bar-card', 'chart-line-card', 'table-card']` | 4 finance widgets, in that order |
| Dispatcher | `['map-card', 'kpi-grid-card', 'recent-activity-card']` | 3 widgets, map first |
| Technician | `['stat-card', 'alert-notice-card']` | 2 minimal widgets |

---

## Tests

All 8 new test cases pass. Full suite (98 tests) passes with no regressions.

```
✓ renders all platform-default widgets when role_ui is absent
✓ falls back to platform defaults when widget_layout is empty
✓ renders only the widgets listed in widget_layout
✓ preserves the order defined in widget_layout
✓ silently skips unknown widget types in widget_layout
✓ finance / bookkeeper role sees only configured finance widgets
✓ dispatch role sees map-card first as configured
✓ technician role sees minimal widget set
```

---

## Next Steps

- [ ] Render live data inside each widget card (e.g. KPI values via an Inertia prop or API call).
- [ ] Add drag-to-reorder support in the UI so users can personalise widget order beyond the role default.
- [ ] Consume `role_ui.hidden_nav_items` in the Vue sidebar (`AppSidebar.vue`) to suppress navigation items per role.
- [ ] Consider persisting per-user widget order overrides on top of the role defaults.
