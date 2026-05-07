<?php

namespace App\Platform\Modules;

class VoiceManifestRegistry extends ModuleDeclarationRegistry
{
    public function __construct()
    {
        parent::__construct([
            'pipeline' => 'pipeline_step',
            'confirmation_phrases' => 'confirmation_phrase',
        ]);
    }
}
