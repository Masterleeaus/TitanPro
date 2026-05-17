<?php

namespace Modules\TitanEchoAssist\AI\Pipelines;

use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\Services\KnowledgeRetriever;

class RagPipeline
{
    public function run(string $query, int $chatbotId, int $limit = 5): array
    {
        try {
            return app(KnowledgeRetriever::class)->retrieve($query, (string) $chatbotId, $limit);
        } catch (\Throwable $e) {
            Log::warning('RagPipeline: query failed.', ['error' => $e->getMessage()]);

            return [];
        }
    }
}
