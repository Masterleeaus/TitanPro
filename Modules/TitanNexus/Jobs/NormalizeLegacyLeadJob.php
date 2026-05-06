<?php

namespace Modules\TitanNexus\Jobs;

use Modules\TitanNexus\Services\UnifiedLeadIngestService;

class NormalizeLegacyLeadJob
{
    public function __construct(public array $payload, public string $source = 'legacy') {}

    public function handle(UnifiedLeadIngestService $ingest): void
    {
        $ingest->ingest($this->payload, $this->source);
    }
}
