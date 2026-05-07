<?php

namespace App\Platform\Modules;

class TableRegistry extends ModuleDeclarationRegistry
{
    public function __construct()
    {
        parent::__construct([
            'tables' => 'table',
            'primary_tables' => 'table',
        ]);
    }
}
