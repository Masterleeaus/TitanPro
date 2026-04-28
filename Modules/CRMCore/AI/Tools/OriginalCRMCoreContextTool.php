<?php

namespace Modules\CRMCore\AI\Tools;

class OriginalCRMCoreContextTool
{
    public function handle(): array
    {
        return [
            'preserved_source' => base_path('Modules/CRMCore/_original_source'),
            'note' => 'Original CRMCore source is preserved for inspection and future migration.',
        ];
    }
}
