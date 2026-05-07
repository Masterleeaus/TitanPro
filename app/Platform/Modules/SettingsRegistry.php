<?php

namespace App\Platform\Modules;

class SettingsRegistry extends ModuleDeclarationRegistry
{
    public function __construct()
    {
        parent::__construct([
            'settings' => 'setting',
        ]);
    }
}
