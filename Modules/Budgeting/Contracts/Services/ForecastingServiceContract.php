<?php

declare(strict_types=1);

namespace Modules\Budgeting\Contracts\Services;

use Modules\Budgeting\Models\ForecastScenario;

interface ForecastingServiceContract
{
    public function generateScenario(array $data): ForecastScenario;

    public function compareScenarios(array $scenarioIds): array;
}
