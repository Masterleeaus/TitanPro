<?php

namespace App\Http\Controllers\Platform\Concerns;

use Nwidart\Modules\Module;

trait ParsesModuleManifest
{
    private function manifestFor(Module $module): array
    {
        $manifest = $module->json()->toArray();

        return is_array($manifest) ? $manifest : [];
    }
}
