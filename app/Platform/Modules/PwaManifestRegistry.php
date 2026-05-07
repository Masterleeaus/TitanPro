<?php

namespace App\Platform\Modules;

class PwaManifestRegistry extends ModuleDeclarationRegistry
{
    public function __construct()
    {
        parent::__construct([
            'items' => 'item',
            'screens' => 'screen',
            'capabilities' => 'capability',
        ]);
    }

    /**
     * @param  array<string, mixed>  $manifest
     * @return array<string, mixed>
     */
    public function mergeIntoGlobalManifest(array $manifest): array
    {
        $manifest['modules'] = $this->manifests();

        return $manifest;
    }
}
