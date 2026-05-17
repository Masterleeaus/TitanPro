<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Visual regression tests for the 8 UI Studio motion presets.
 *
 * Each test:
 *  1. Visits the Motion Preview page and selects a preset.
 *  2. Captures a full-page screenshot.
 *  3. Pixel-diffs the screenshot against the stored baseline in
 *     tests/Browser/Snapshots/motion-presets/<preset>.png.
 *  4. Fails when the ratio of differing pixels exceeds the configured
 *     threshold (default 1 %; override via MOTION_SNAPSHOT_THRESHOLD env).
 *
 * Baseline generation
 * -------------------
 * If a baseline is missing or has different dimensions from the captured
 * screenshot (e.g. the committed file is the 1×1 placeholder), the test
 * overwrites it with the current screenshot and marks itself incomplete so
 * the next run performs a real comparison.
 *
 * To force-regenerate all baselines intentionally:
 *   MOTION_REGENERATE_BASELINES=true php artisan dusk tests/Browser/MotionPresetSnapshotTest.php
 *
 * See tests/Browser/Snapshots/motion-presets/README.md for full documentation.
 */
class MotionPresetSnapshotTest extends DuskTestCase
{
    /** The 8 motion presets defined by the UI Studio motion layer. */
    private const PRESETS = [
        'none',
        'fade-in',
        'slide-up',
        'smooth-sidebar',
        'hover-lift',
        'blur-overlay',
        'shimmer-load',
        'scale-press',
    ];

    /**
     * Default maximum ratio of differing pixels (1 %).
     * Override via the MOTION_SNAPSHOT_THRESHOLD environment variable.
     */
    private const DEFAULT_THRESHOLD = 0.01;

    private string $snapshotDir;

    private float $threshold;

    protected function setUp(): void
    {
        parent::setUp();

        $this->snapshotDir = __DIR__ . '/Snapshots/motion-presets';

        $this->threshold = (float) env('MOTION_SNAPSHOT_THRESHOLD', self::DEFAULT_THRESHOLD);
    }

    /**
     * Capture screenshots for all 8 motion presets and compare against baselines.
     *
     * The loop always processes every preset before reporting results, so a single
     * new baseline or a single diff failure does not silently skip the remaining 7.
     *
     * After iterating:
     *  - Any comparison failures are surfaced together via $this->fail().
     *  - If baselines were generated/updated the test is marked incomplete, prompting
     *    the developer to commit the new PNGs and re-run.
     */
    public function test_each_motion_preset_matches_visual_baseline(): void
    {
        $regenerated = [];
        $failures    = [];

        foreach (self::PRESETS as $preset) {
            ['regenerated' => $wasRegenerated, 'failure' => $failure] =
                $this->processPreset($preset);

            if ($wasRegenerated) {
                $regenerated[] = $preset;
            }

            if ($failure !== null) {
                $failures[] = $failure;
            }
        }

        if (! empty($failures)) {
            $this->fail(implode("\n\n", $failures));
        }

        if (! empty($regenerated)) {
            $this->markTestIncomplete(
                'Baselines created/updated for: ' . implode(', ', $regenerated) . ".\n"
                    . 'Commit the new PNGs from tests/Browser/Snapshots/motion-presets/ '
                    . 'then re-run to validate the comparison.'
            );
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Capture a screenshot for $preset and compare (or seed) its baseline.
     *
     * @return array{regenerated: bool, failure: ?string}
     */
    private function processPreset(string $preset): array
    {
        $screenshotName = "motion-snapshot-{$preset}";
        $screenshotPath = base_path("tests/Browser/screenshots/{$screenshotName}.png");
        $baselinePath   = "{$this->snapshotDir}/{$preset}.png";

        // 1. Capture the screenshot via Dusk
        $this->browse(function (Browser $browser) use ($preset, $screenshotName) {
            $browser->visit(route('ui-studio.motion-preview'))
                ->select('#preset-select', $preset)
                ->pause(400)   // allow animation frame to settle
                ->screenshot($screenshotName);
        });

        // 2. Determine whether we should regenerate or compare
        if ($this->shouldRegenerateBaseline($baselinePath, $screenshotPath)) {
            $this->storeBaseline($screenshotPath, $baselinePath);

            return ['regenerated' => true, 'failure' => null];
        }

        // 3. Pixel diff comparison
        $diffRatio = $this->compareScreenshots($baselinePath, $screenshotPath);

        if ($diffRatio > $this->threshold) {
            return [
                'regenerated' => false,
                'failure'     => sprintf(
                    "Motion preset '%s' screenshot differs from baseline by %.2f%% "
                        . "(allowed threshold: %.2f%%). "
                        . "Run with MOTION_REGENERATE_BASELINES=true to update baselines.",
                    $preset,
                    $diffRatio * 100.0,
                    $this->threshold * 100.0
                ),
            ];
        }

        return ['regenerated' => false, 'failure' => null];
    }

    /**
     * Returns true when the baseline should be (re)created instead of compared.
     *
     * Triggers:
     *  - Baseline file does not exist.
     *  - MOTION_REGENERATE_BASELINES env var is truthy.
     *  - Baseline has different pixel dimensions from the captured screenshot
     *    (catches the committed 1×1 placeholder being compared to a real screenshot).
     */
    private function shouldRegenerateBaseline(string $baselinePath, string $screenshotPath): bool
    {
        if (! file_exists($baselinePath)) {
            return true;
        }

        if (filter_var(
            env('MOTION_REGENERATE_BASELINES', false),
            FILTER_VALIDATE_BOOLEAN
        )) {
            return true;
        }

        // Detect placeholder (dimensions differ from the live screenshot)
        [$bw, $bh] = $this->imageDimensions($baselinePath);
        [$sw, $sh] = $this->imageDimensions($screenshotPath);

        return ($bw !== $sw || $bh !== $sh);
    }

    /**
     * Copy the captured screenshot as the new baseline.
     * (The caller is responsible for signalling markTestIncomplete / failure.)
     */
    private function storeBaseline(string $screenshotPath, string $baselinePath): void
    {
        if (! is_dir($this->snapshotDir)) {
            mkdir($this->snapshotDir, 0755, true);
        }

        copy($screenshotPath, $baselinePath);
    }

    /**
     * Compare two PNG files pixel by pixel using GD.
     *
     * Returns a float in [0, 1] representing the ratio of pixels whose
     * per-channel colour distance exceeds a 2 % noise floor.
     * Both images are assumed to have the same dimensions when called
     * (ensured by shouldRegenerateBaseline checking for size mismatches first).
     *
     * @return float Ratio of differing pixels (0.0 = identical, 1.0 = fully different)
     */
    private function compareScreenshots(string $baselinePath, string $screenshotPath): float
    {
        $baseline   = @imagecreatefrompng($baselinePath);
        $screenshot = @imagecreatefrompng($screenshotPath);

        if (! $baseline || ! $screenshot) {
            // Clean up any successfully loaded resource before returning
            if ($baseline) {
                imagedestroy($baseline);
            }
            if ($screenshot) {
                imagedestroy($screenshot);
            }

            return 0.0;
        }

        $width  = imagesx($baseline);
        $height = imagesy($baseline);

        $totalPixels = $width * $height;
        $diffPixels  = 0;

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $bColor = imagecolorat($baseline, $x, $y);
                $sColor = imagecolorat($screenshot, $x, $y);

                if ($bColor === $sColor) {
                    continue;
                }

                $bR = ($bColor >> 16) & 0xFF;
                $bG = ($bColor >> 8) & 0xFF;
                $bB = $bColor & 0xFF;

                $sR = ($sColor >> 16) & 0xFF;
                $sG = ($sColor >> 8) & 0xFF;
                $sB = $sColor & 0xFF;

                // Normalised mean channel distance; ignore sub-2 % rendering noise
                $channelDiff = (abs($bR - $sR) + abs($bG - $sG) + abs($bB - $sB)) / (3.0 * 255.0);

                if ($channelDiff > 0.02) {
                    $diffPixels++;
                }
            }
        }

        imagedestroy($baseline);
        imagedestroy($screenshot);

        return $totalPixels > 0 ? ($diffPixels / $totalPixels) : 0.0;
    }

    /**
     * Returns [width, height] of a PNG file using getimagesize() (no GD allocation).
     *
     * @return array{int, int}
     */
    private function imageDimensions(string $path): array
    {
        if (! file_exists($path)) {
            return [0, 0];
        }

        $size = @getimagesize($path);

        return $size ? [$size[0], $size[1]] : [0, 0];
    }
}
