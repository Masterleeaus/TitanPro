<?php

namespace Modules\CallingAgent\AI\Tools;

use Modules\CallingAgent\AI\Pipelines\OutcomeExtractionPipeline;

final class OutcomeIntelligenceTool
{
    public function __construct(private readonly OutcomeExtractionPipeline $pipeline = new OutcomeExtractionPipeline()) {}

    public function execute(array $input): array
    {
        $transcript = $input['transcript'] ?? [];
        $context = is_array($input['context'] ?? null) ? $input['context'] : [];
        $outcome = $this->pipeline->extract($transcript, $context);

        return [
            'outcome' => $outcome->toArray(),
        ];
    }
}
