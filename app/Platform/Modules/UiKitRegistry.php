<?php

namespace App\Platform\Modules;

class UiKitRegistry extends ModuleDeclarationRegistry
{
    public function __construct()
    {
        parent::__construct([
            'components' => 'component',
        ]);
    }

    /**
     * @return array<int, array{module: string, type: string, key: string, data: mixed}>
     */
    public function components(?string $module = null): array
    {
        return $this->byType('component', $module);
    }
}
