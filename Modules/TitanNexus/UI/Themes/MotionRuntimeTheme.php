<?php

namespace Modules\TitanNexus\UI\Themes;

use Modules\TitanNexus\UI\Forms\ModuleSettingsForm;

final class MotionRuntimeTheme
{
    public static function make(): array
    {
        $schema = ModuleSettingsForm::schema();
        $motionTab = self::findMotionTab($schema);
        $uiTokens = UiTokens::make();

        return [
            'generated_css' => $motionTab['generated_css'] ?? $uiTokens['generated_css'],
            'reduced_motion_media_query' => $motionTab['reduced_motion_media_query'] ?? $uiTokens['reduced_motion_media_query'],
            'default_preset' => $uiTokens['tokens']['--motion-preset'] ?? 'none',
        ];
    }

    private static function findMotionTab(array $schema): array
    {
        foreach ($schema['tabs'] ?? [] as $tab) {
            if (($tab['id'] ?? null) === 'motion') {
                return $tab;
            }
        }

        return [];
    }
}
