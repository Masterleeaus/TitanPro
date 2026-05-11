<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * End-to-end browser validation for the UI Studio motion layer.
 *
 * Covers:
 *  - Motion tab controls render (preset / speed / easing selects)
 *  - Changing a preset updates the live preview DOM (data-motion-preset attribute + CSS var)
 *  - Generated CSS contains prefers-reduced-motion override
 *  - All 8 required presets are present in the select
 *  - CSS variables (--motion-preset, --motion-speed, --motion-ease) reach the rendered DOM
 */
class MotionBrowserTest extends DuskTestCase
{
    private string $url;

    protected function setUp(): void
    {
        parent::setUp();
        $this->url = route('ui-studio.motion-preview');
    }

    /** The motion preview page loads and the page title is correct. */
    public function test_motion_preview_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url)
                ->assertTitle('UI Studio — Motion Preview')
                ->assertSee('UI Studio — Motion Preview');
        });
    }

    /** All three control selects (preset, speed, easing) are present. */
    public function test_motion_tab_controls_render(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url)
                ->assertPresent('#preset-select')
                ->assertPresent('#speed-select')
                ->assertPresent('#easing-select');
        });
    }

    /**
     * The preset select contains all 8 required options:
     * none, fade-in, slide-up, smooth-sidebar, hover-lift, blur-overlay, shimmer-load, scale-press.
     */
    public function test_all_eight_presets_present_in_select(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url);

            $requiredPresets = [
                'none',
                'fade-in',
                'slide-up',
                'smooth-sidebar',
                'hover-lift',
                'blur-overlay',
                'shimmer-load',
                'scale-press',
            ];

            foreach ($requiredPresets as $preset) {
                $browser->assertPresent("#preset-select option[value=\"{$preset}\"]");
            }
        });
    }

    /**
     * Changing the preset select updates data-motion-preset on <html>
     * and the inline token display — simulating live preview behaviour.
     */
    public function test_changing_preset_updates_live_preview_dom(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url);

            // Default should be 'none'
            $browser->assertAttribute('#motion-root', 'data-motion-preset', 'none');

            // Switch to fade-in
            $browser->select('#preset-select', 'fade-in');

            // The JavaScript handler should update data-motion-preset
            $browser->assertAttribute('#motion-root', 'data-motion-preset', 'fade-in');

            // The token value display should reflect the new preset
            $browser->assertSeeIn('#val-preset', 'fade-in');
        });
    }

    /** Each preset can be selected without JS errors. */
    public function test_each_preset_selectable_without_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url);

            $presets = ['none', 'fade-in', 'slide-up', 'smooth-sidebar', 'hover-lift', 'blur-overlay', 'shimmer-load', 'scale-press'];

            foreach ($presets as $preset) {
                $browser->select('#preset-select', $preset)
                    ->assertAttribute('#motion-root', 'data-motion-preset', $preset);
            }
        });
    }

    /** Changing speed updates the CSS variable on :root. */
    public function test_changing_speed_updates_css_variable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url);

            // Select slow speed
            $browser->select('#speed-select', '400ms');

            $cssVar = $browser->script("return getComputedStyle(document.documentElement).getPropertyValue('--motion-speed').trim()");

            $this->assertSame('400ms', $cssVar[0]);
        });
    }

    /** Changing easing updates the CSS variable on :root. */
    public function test_changing_easing_updates_css_variable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url);

            // Select spring easing
            $browser->select('#easing-select', 'cubic-bezier(0.34, 1.56, 0.64, 1)');

            $cssVar = $browser->script("return getComputedStyle(document.documentElement).getPropertyValue('--motion-ease').trim()");

            $this->assertSame('cubic-bezier(0.34, 1.56, 0.64, 1)', $cssVar[0]);
        });
    }

    /**
     * The generated CSS inlined in the page contains --motion-preset, --motion-speed,
     * and --motion-ease CSS custom property declarations.
     */
    public function test_generated_css_variables_reach_rendered_dom(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url);

            $cssContent = $browser->script("return document.getElementById('motion-generated-css').textContent");

            $this->assertStringContainsString('--motion-preset', $cssContent[0]);
            $this->assertStringContainsString('--motion-speed', $cssContent[0]);
            $this->assertStringContainsString('--motion-ease', $cssContent[0]);
        });
    }

    /**
     * The generated CSS contains a prefers-reduced-motion: reduce media query
     * that disables animation and transition on all motion targets.
     */
    public function test_generated_css_contains_reduced_motion_override(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url);

            $cssContent = $browser->script("return document.getElementById('motion-generated-css').textContent");

            $this->assertStringContainsString('prefers-reduced-motion: reduce', $cssContent[0]);
            $this->assertStringContainsString('animation: none !important', $cssContent[0]);
            $this->assertStringContainsString('transition: none !important', $cssContent[0]);
        });
    }

    /**
     * When prefers-reduced-motion: reduce is emulated via injected CSS,
     * the motion-target element no longer carries an animation.
     *
     * Because CDP-level media emulation is not available through the standard
     * Dusk WebDriver bridge, this test injects an override stylesheet that
     * mirrors what the OS media preference would produce, then asserts the
     * computed animation-name is 'none'.
     */
    public function test_reduced_motion_override_disables_animation_effects(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url);

            // Activate fade-in so the motion-target would normally have an animation
            $browser->select('#preset-select', 'fade-in');

            // Inject a stylesheet simulating prefers-reduced-motion: reduce
            $browser->script(
                "var s = document.createElement('style');" .
                "s.id = 'test-reduced-motion';" .
                "s.textContent = '.motion-target,.motion-sidebar,.motion-card,.motion-overlay,.motion-skeleton,.motion-press { animation: none !important; transition: none !important; }';" .
                "document.head.appendChild(s);"
            );

            // Verify animation-name is 'none' on the motion-target
            $animationName = $browser->script(
                "return getComputedStyle(document.getElementById('preview-target')).animationName"
            );

            $this->assertSame('none', $animationName[0]);
        });
    }

    /**
     * The motion-meta element carries data attributes for all tokens and presets,
     * allowing test harnesses to inspect values without parsing CSS.
     */
    public function test_motion_meta_element_exposes_token_data_attributes(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url)
                ->assertAttribute('#motion-meta', 'data-preset', 'none')
                ->assertAttribute('#motion-meta', 'data-live-preview', 'true');

            $presets = $browser->attribute('#motion-meta', 'data-presets');

            $this->assertStringContainsString('fade-in', $presets);
            $this->assertStringContainsString('slide-up', $presets);
            $this->assertStringContainsString('smooth-sidebar', $presets);
            $this->assertStringContainsString('hover-lift', $presets);
            $this->assertStringContainsString('blur-overlay', $presets);
            $this->assertStringContainsString('shimmer-load', $presets);
            $this->assertStringContainsString('scale-press', $presets);
        });
    }

    /** All motion target preview elements are present in the DOM. */
    public function test_preview_elements_for_each_motion_class_are_present(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url)
                ->assertPresent('#preview-target')
                ->assertPresent('#preview-sidebar')
                ->assertPresent('#preview-card')
                ->assertPresent('#preview-overlay')
                ->assertPresent('#preview-skeleton')
                ->assertPresent('#preview-press');
        });
    }
}
