<?php

namespace App\Http\Controllers\UiStudio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Modules\TitanNexus\UI\Forms\ModuleSettingsForm;
use Modules\TitanNexus\UI\Themes\UiTokens;

/**
 * Renders a self-contained motion preview page used exclusively by browser tests.
 * Only accessible in local and testing environments.
 */
class MotionPreviewController extends Controller
{
    public function __invoke(): Response
    {
        $uiTokens = UiTokens::make();
        $schema = ModuleSettingsForm::schema();

        $motionTab = collect($schema['tabs'] ?? [])->firstWhere('id', 'motion');

        return response()->view('ui-studio.motion-preview', [
            'uiTokens' => $uiTokens,
            'motionTab' => $motionTab,
        ]);
    }
}
