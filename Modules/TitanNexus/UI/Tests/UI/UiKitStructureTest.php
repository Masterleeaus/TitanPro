<?php

namespace Modules\TitanNexus\Tests\UI;

use Modules\TitanNexus\UI\Forms\ModuleSettingsForm;
use Modules\TitanNexus\UI\Themes\UiTokens;
use PHPUnit\Framework\TestCase;

final class UiKitStructureTest extends TestCase
{
    public function test_motion_tokens_expose_required_presets_and_tokens(): void
    {
        $tokens = UiTokens::make();

        $this->assertSame('none', $tokens['tokens']['--motion-preset']);
        $this->assertSame('250ms', $tokens['tokens']['--motion-speed']);
        $this->assertSame('cubic-bezier(0.16, 1, 0.3, 1)', $tokens['tokens']['--motion-ease']);

        $this->assertSame(
            ['none', 'fade-in', 'slide-up', 'smooth-sidebar', 'hover-lift', 'blur-overlay', 'shimmer-load', 'scale-press'],
            $tokens['presets']
        );
    }

    public function test_generated_css_includes_reduced_motion_override(): void
    {
        $generatedCss = UiTokens::make()['generated_css'];

        $this->assertStringContainsString(':root[data-motion-preset="fade-in"] .motion-target', $generatedCss);
        $this->assertStringContainsString(':root[data-motion-preset="slide-up"] .motion-target', $generatedCss);
        $this->assertStringContainsString(':root[data-motion-preset="smooth-sidebar"] .motion-sidebar', $generatedCss);
        $this->assertStringContainsString(':root[data-motion-preset="hover-lift"] .motion-card:hover', $generatedCss);
        $this->assertStringContainsString(':root[data-motion-preset="blur-overlay"] .motion-overlay', $generatedCss);
        $this->assertStringContainsString(':root[data-motion-preset="shimmer-load"] .motion-skeleton', $generatedCss);
        $this->assertStringContainsString(':root[data-motion-preset="scale-press"] .motion-press:active', $generatedCss);
        $this->assertStringContainsString('@media (prefers-reduced-motion: reduce)', $generatedCss);
    }

    public function test_module_settings_form_contains_motion_tab_and_live_preview(): void
    {
        $schema = ModuleSettingsForm::schema();
        $motionTab = $schema['tabs'][0];

        $this->assertSame('motion', $motionTab['id']);
        $this->assertSame('Motion', $motionTab['label']);
        $this->assertTrue($motionTab['live_preview']);
        $this->assertSame('live', $motionTab['preview']['mode']);

        $fieldNames = array_map(static fn (array $field): string => $field['name'], $motionTab['fields']);
        $this->assertSame(['motion_preset', 'motion_speed', 'motion_ease'], $fieldNames);
    }
}
