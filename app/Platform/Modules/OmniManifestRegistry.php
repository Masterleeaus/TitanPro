<?php

namespace App\Platform\Modules;

class OmniManifestRegistry extends ModuleDeclarationRegistry
{
    public function __construct()
    {
        parent::__construct([
            'items' => 'item',
            'channels' => 'channel',
            'events' => 'event',
        ]);
    }
}
