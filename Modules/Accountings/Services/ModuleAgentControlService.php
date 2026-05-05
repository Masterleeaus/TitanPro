<?php

namespace Modules\Accountings\Services;

class ModuleAgentControlService
{
    public function manifest(): array
    {
        $path = module_path('Accountings', 'AI/Control/control.manifest.json');
        return is_file($path) ? json_decode(file_get_contents($path), true) : [];
    }

    public function uiManifest(): array
    {
        $path = module_path('Accountings', 'UI/manifests/ui.json');
        return is_file($path) ? json_decode(file_get_contents($path), true) : [];
    }
}
