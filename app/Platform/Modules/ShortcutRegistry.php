<?php

namespace App\Platform\Modules;

class ShortcutRegistry extends ModuleDeclarationRegistry
{
    public function __construct()
    {
        parent::__construct([
            'shortcuts' => 'shortcut',
        ]);
    }
}
