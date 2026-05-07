<?php

namespace App\Platform\Modules;

class DashboardRegistry extends ModuleDeclarationRegistry
{
    public function __construct()
    {
        parent::__construct([
            'widgets' => 'widget',
            'layouts' => 'layout',
            'cards' => 'card',
        ]);
    }
}
