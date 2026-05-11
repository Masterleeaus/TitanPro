
<?php

declare(strict_types=1);

namespace App\Extensions\TitanPulse\System\Services;

use App\Extensions\TitanPulse\System\Core\PulseKernel\Services\WorkflowEvaluator;
use App\Extensions\TitanPulse\System\Core\PulseKernel\Services\WorkflowRunRecorder;
use App\Extensions\TitanPulse\System\Core\PulseEngine\Services\ScheduledPulseSourceService;

class PulseRuntimeManager
{
    public function __construct(
        protected PulseEngine $engine,
        protected WorkflowEvaluator $evaluator,
        protected WorkflowRunRecorder $recorder,
        protected ScheduledPulseSourceService $scheduledSources,
    ) {}

    public function summary(): array
    {
        return [
            'engine' => PulseEngine::class,
            'kernel_evaluator' => WorkflowEvaluator::class,
            'run_recorder' => WorkflowRunRecorder::class,
            'scheduled_sources' => ScheduledPulseSourceService::class,
        ];
    }
}
