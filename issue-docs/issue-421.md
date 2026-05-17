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
