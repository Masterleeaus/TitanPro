<?php

namespace Modules\TitanNexus\Services;

use Modules\TitanNexus\DTOs\LeadDTO;
use Modules\TitanNexus\Events\LeadQualified;

class UnifiedLeadIngestService
{
    public function ingest(array $payload, string $source = 'legacy'): LeadDTO
    {
        $lead = new LeadDTO(
            name: $payload['name'] ?? $payload['full_name'] ?? $payload['contact_name'] ?? 'Unknown Lead',
            email: $payload['email'] ?? null,
            phone: $payload['phone'] ?? $payload['mobile'] ?? null,
            company: $payload['company'] ?? $payload['business_name'] ?? null,
            source: $source,
            meta: $payload
        );

        if (($payload['qualified'] ?? false) === true) {
            event(new LeadQualified($lead));
        }

        return $lead;
    }
}
