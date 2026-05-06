<?php

namespace Modules\TitanNexus\Services;

class ChannelOrchestratorService
{
    public function chooseChannel(array $lead, array $campaign): string
    {
        if (! empty($lead['phone']) && ($campaign['prefer_sms'] ?? false)) {
            return 'sms';
        }
        if (! empty($lead['phone']) && ($campaign['prefer_voice'] ?? false)) {
            return 'voice';
        }
        if (! empty($lead['whatsapp']) || ($campaign['prefer_whatsapp'] ?? false)) {
            return 'whatsapp';
        }
        return 'email';
    }

    public function route(array $lead, string $message, array $campaign = []): array
    {
        return [
            'channel' => $this->chooseChannel($lead, $campaign),
            'lead' => $lead,
            'message' => $message,
            'campaign' => $campaign,
            'status' => 'queued',
        ];
    }
}
