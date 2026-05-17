<?php

declare(strict_types=1);

namespace Modules\Budgeting\Actions\Forecasting;

use Modules\Budgeting\Contracts\Services\ForecastingServiceContract;
use Modules\Budgeting\Models\ForecastScenario;

class GenerateForecastScenarioAction
{
    public function __construct(protected ForecastingServiceContract $service) {}

    public function handle(array $data): ForecastScenario
    {
        return $this->service->generateScenario($data);
    }
}
