<?php

namespace Modules\TitanNexus\Tests\UI;

use Modules\TitanNexus\UI\Forms\ModuleSettingsForm;
use Modules\TitanNexus\UI\Themes\MotionRuntimeTheme;
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
        $motionTab = $this->findTabById($schema, 'motion');

        $this->assertNotNull($motionTab);
        $this->assertSame('motion', $motionTab['id']);
        $this->assertSame('Motion', $motionTab['label']);
        $this->assertTrue($motionTab['live_preview']);
        $this->assertSame('live', $motionTab['preview']['mode']);

        $fieldNames = [];
        foreach ($motionTab['fields'] as $field) {
            $fieldNames[] = $field['name'];
        }

        $this->assertSame(['motion_preset', 'motion_speed', 'motion_ease'], $fieldNames);
    }

    public function test_runtime_theme_uses_motion_tab_css_for_renderer_injection(): void
    {
        $schema = ModuleSettingsForm::schema();
        $motionTab = $this->findTabById($schema, 'motion');
        $runtimeTheme = MotionRuntimeTheme::make();

        $this->assertNotNull($motionTab);
        $this->assertSame($motionTab['generated_css'], $runtimeTheme['generated_css']);
        $this->assertSame($motionTab['reduced_motion_media_query'], $runtimeTheme['reduced_motion_media_query']);
        $this->assertSame(UiTokens::make()['tokens']['--motion-preset'], $runtimeTheme['default_preset']);
    }

    private function findTabById(array $schema, string $tabId): ?array
    {
        foreach ($schema['tabs'] ?? [] as $tab) {
            if (($tab['id'] ?? null) === $tabId) {
                return $tab;
            }
        }

        return null;
    }
}
