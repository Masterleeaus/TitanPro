<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Reporting;

use Modules\Dispatch\Services\Analytics\DispatchKpiService;

class GenerateDispatchSummaryAction
{
    public function __construct(private readonly DispatchKpiService $kpis) {}

    public function handle(?string $from = null, ?string $to = null): array
    {
        return $this->kpis->summary($from, $to);
    }
}
