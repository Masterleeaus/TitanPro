<?php

namespace App\Platform\Modules;

class ChannelManifestRegistry extends ModuleDeclarationRegistry
{
    public function __construct()
    {
        parent::__construct([
            'channels' => 'channel',
            'supported_channels' => 'channel',
            'events' => 'event',
        ]);
    }
}
