# Motion Preset Snapshot Baselines

This directory holds the baseline PNG screenshots used by `MotionPresetSnapshotTest`
for visual regression testing of the 8 UI Studio motion presets.

## Baseline files

| File | Preset |
|------|--------|
| `none.png` | none / no animation |
| `fade-in.png` | Fade In |
| `slide-up.png` | Slide Up |
| `smooth-sidebar.png` | Smooth Sidebar |
| `hover-lift.png` | Hover Lift |
| `blur-overlay.png` | Blur Overlay |
| `shimmer-load.png` | Shimmer Load |
| `scale-press.png` | Scale Press |

> **Note:** The files committed here are 1×1 pixel placeholders.
> They are automatically replaced with real full-resolution screenshots on the
> first Dusk run (see *Regenerating Baselines* below).

---

## How the test works

`MotionPresetSnapshotTest::test_each_motion_preset_matches_visual_baseline()` iterates
all 8 presets. For each preset it:

1. Visits the Motion Preview page (`/titan-ui-studio/motion-preview`).
2. Selects the preset in the `#preset-select` control.
3. Waits 400 ms for any animation to settle.
4. Takes a full-page screenshot.
5. Compares the screenshot against the baseline PNG with a pixel-diff algorithm.
6. Fails if the ratio of differing pixels exceeds the configured threshold
   (default **1 %**; override with the `MOTION_SNAPSHOT_THRESHOLD` env variable).

If a baseline does **not yet exist**, or is detected as a placeholder (dimensions
differ from the captured screenshot), the test captures a new baseline and marks
itself **incomplete** — subsequent runs then perform the comparison.

---

## Regenerating baselines after intentional visual changes

Run with the `MOTION_REGENERATE_BASELINES=true` environment variable to overwrite
**all** baseline files with the latest screenshots:

```bash
MOTION_REGENERATE_BASELINES=true php artisan dusk tests/Browser/MotionPresetSnapshotTest.php
```

After regeneration, review the new PNGs visually, then commit them to the
repository so future CI runs compare against the updated visuals.

### In CI (GitHub Actions)

Use the `workflow_dispatch` event with the `regenerate_baselines` input set to
`true`. The workflow will regenerate all baselines, commit the updated files, and
push them to the branch:

```
Actions → Motion Browser Validation → Run workflow → ☑ Regenerate snapshot baselines
```

---

## Diff threshold

The default threshold is **1 % differing pixels** (`MOTION_SNAPSHOT_THRESHOLD=0.01`).

To allow a higher tolerance (e.g., for minor font-rendering differences across OS):

```bash
MOTION_SNAPSHOT_THRESHOLD=0.03 php artisan dusk tests/Browser/MotionPresetSnapshotTest.php
```

---

## Troubleshooting

- **"Baseline generated … Re-run tests"** — The placeholder was replaced on first run.
  Commit the new PNGs and re-run the test to validate the comparison.
- **Diff artifacts** — When a CI run fails, the workflow uploads a
  `dusk-motion-snapshot-diffs` artifact containing both the baseline and the
  captured screenshot for each failing preset. Download the artifact to inspect
  the visual difference.
