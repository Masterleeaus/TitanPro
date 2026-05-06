<?php

namespace Modules\TitanNexus\Services\LeadBridge;

class LegacyLeadPipelineBridge
{
    public function mapLegacyLead(array $legacyLead): array
    {
        return [
            'external_id' => $legacyLead['id'] ?? $legacyLead['lead_id'] ?? null,
            'name' => $legacyLead['name'] ?? $legacyLead['contact_name'] ?? null,
            'email' => $legacyLead['email'] ?? null,
            'phone' => $legacyLead['phone'] ?? $legacyLead['mobile'] ?? null,
            'stage' => $legacyLead['stage'] ?? $legacyLead['status'] ?? 'new',
            'source' => $legacyLead['source'] ?? 'legacy_leads_base',
            'raw' => $legacyLead,
        ];
    }
}
