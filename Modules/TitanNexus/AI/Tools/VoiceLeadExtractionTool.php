<?php

namespace Modules\TitanNexus\AI\Tools;

class VoiceLeadExtractionTool
{
    public function extract(array $conversation): array
    {
        return [
            'intent' => $conversation['intent'] ?? 'unknown',
            'contact' => $conversation['contact'] ?? [],
            'service_need' => $conversation['service_need'] ?? null,
            'urgency' => $conversation['urgency'] ?? null,
            'raw' => $conversation,
        ];
    }
}
