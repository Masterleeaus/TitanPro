<?php

namespace Modules\TitanNexus\UI\Forms;

use Modules\TitanNexus\UI\Themes\UiTokens;


final class ModuleSettingsForm
{
    public static function schema(): array
    {
        $uiTokens = UiTokens::make();

        return [
            'tabs' => [
                [
                    'id' => 'motion',
                    'label' => 'Motion',
                    'live_preview' => true,
                    'description' => 'Configure animation presets, speed, and easing for the UI Studio theme engine.',
                    'fields' => [
                        [
                            'type' => 'select',
                            'name' => 'motion_preset',
                            'label' => 'Motion Preset',
                            'token' => '--motion-preset',
                            'default' => $uiTokens['tokens']['--motion-preset'],
                            'options' => $uiTokens['preset_options'],
                        ],
                        [
                            'type' => 'select',
                            'name' => 'motion_speed',
                            'label' => 'Motion Speed',
                            'token' => '--motion-speed',
                            'default' => $uiTokens['tokens']['--motion-speed'],
                            'options' => $uiTokens['speed_options'],
                        ],
                        [
                            'type' => 'select',
                            'name' => 'motion_ease',
                            'label' => 'Motion Easing',
                            'token' => '--motion-ease',
                            'default' => $uiTokens['tokens']['--motion-ease'],
                            'options' => $uiTokens['easing_options'],
                        ],
                    ],
                    'preview' => [
                        'mode' => 'live',
                        'description' => 'Live preview updates card hover, sidebar transitions, overlays, skeleton shimmer, and button press states in real time.',
                        'targets' => ['card', 'sidebar', 'overlay', 'skeleton', 'button'],
                    ],
                    'generated_css' => $uiTokens['generated_css'],
                    'reduced_motion_media_query' => $uiTokens['reduced_motion_media_query'],
                ],
            ],
        ];
    }

}
