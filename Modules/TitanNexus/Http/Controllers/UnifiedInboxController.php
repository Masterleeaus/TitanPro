<?php

namespace Modules\TitanNexus\Http\Controllers;

use Modules\TitanNexus\Services\UnifiedLeadIngestService;

class UnifiedInboxController
{
    public function store(array $request, UnifiedLeadIngestService $ingest): array
    {
        $lead = $ingest->ingest($request, $request['source'] ?? 'inbox');
        return ['ok' => true, 'lead' => $lead];
    }
}
