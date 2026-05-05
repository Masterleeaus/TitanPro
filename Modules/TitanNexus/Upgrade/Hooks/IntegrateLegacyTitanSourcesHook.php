<?php

namespace Modules\TitanNexus\Upgrade\Hooks;

class IntegrateLegacyTitanSourcesHook
{
    public function __invoke(): array
    {
        return [
            'preserved_sources' => ['TitanNexus', 'TitanNexusBase', 'TitanLeadsBase'],
            'adapters' => ['voice_webhooks', 'sms_channels', 'lead_pipeline_bridge'],
        ];
    }
}
