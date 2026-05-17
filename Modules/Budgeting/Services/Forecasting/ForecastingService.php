<?php

declare(strict_types=1);

namespace Modules\Budgeting\Services\Forecasting;

use Illuminate\Support\Facades\DB;
use Modules\Budgeting\Contracts\Services\ForecastingServiceContract;
use Modules\Budgeting\Events\Domain\ForecastScenarioGenerated;
use Modules\Budgeting\Models\ForecastScenario;

class ForecastingService implements ForecastingServiceContract
{
    public function __construct(protected ForecastScenario $model) {}

    public function generateScenario(array $data): ForecastScenario
    {
        return DB::transaction(function () use ($data): ForecastScenario {
            $scenario = $this->model->newQuery()->create(array_merge($data, [
                'status' => 'draft',
            ]));

            event(new ForecastScenarioGenerated($scenario));

            return $scenario;
        });
    }

    public function compareScenarios(array $scenarioIds): array
    {
        $scenarios = $this->model->newQuery()
            ->whereIn('id', $scenarioIds)
            ->get();

        return $scenarios->map(fn (ForecastScenario $s): array => [
            'id' => $s->id,
            'name' => $s->name,
            'period_start' => $s->period_start,
            'period_end' => $s->period_end,
            'status' => $s->status,
            'scenario_data' => $s->scenario_data,
        ])->toArray();
    }
}
