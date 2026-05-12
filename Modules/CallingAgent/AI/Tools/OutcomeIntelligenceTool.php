<?php

namespace Modules\CallingAgent\AI\Tools;

use Modules\CallingAgent\AI\Pipelines\OutcomeExtractionPipeline;

final class OutcomeIntelligenceTool
{
    private OutcomeExtractionPipeline $pipeline;

    public function __construct(?OutcomeExtractionPipeline $pipeline = null)
    {
        $this->pipeline = $pipeline ?? app(OutcomeExtractionPipeline::class);
    }

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
