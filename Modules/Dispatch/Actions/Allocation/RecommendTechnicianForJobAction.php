<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Allocation;

use Illuminate\Support\Collection;
use Modules\Dispatch\Services\Allocation\TechnicianMatchingService;

class RecommendTechnicianForJobAction
{
    public function __construct(private readonly TechnicianMatchingService $matchingService) {}

    public function execute(array $jobContext = []): Collection
    {
        return $this->matchingService->rank($jobContext)->take($jobContext['limit'] ?? 10);
    }
}
