<?php

namespace Modules\TitanNexus\Services;

class LegacyBridgeService
{
    public function mappedCapabilities(): array
    {
        return [
            'legacy_models' => 'Models/LegacyMarketing + Models/LegacyLead',
            'legacy_events' => 'Events/LegacyLead',
            'legacy_views' => 'Resources/views/legacy-*',
            'channels' => ['email', 'sms', 'voice', 'whatsapp', 'telegram', 'messenger'],
            'ai' => ['knowledge_base', 'embedding', 'generator', 'voice_ai'],
            'lead_pipeline' => ['lead', 'deal', 'pipeline', 'stage', 'task', 'discussion', 'file', 'email', 'call'],
        ];
    }
}
