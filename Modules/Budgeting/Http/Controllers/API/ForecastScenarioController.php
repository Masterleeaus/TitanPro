<?php

declare(strict_types=1);

namespace Modules\Budgeting\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Budgeting\Contracts\Services\ForecastingServiceContract;
use Modules\Budgeting\Http\Resources\ForecastScenarioResource;
use Modules\Budgeting\Jobs\Queued\GenerateForecastScenarioJob;
use Modules\Budgeting\Models\ForecastScenario;

class ForecastScenarioController extends Controller
{
    public function __construct(protected ForecastingServiceContract $service) {}

    public function index(Request $request): JsonResponse
    {
        $scenarios = ForecastScenario::query()
            ->where('company_id', $request->user()->company_id)
            ->latest()
            ->paginate(config('budgeting.pagination.per_page', 25));

        return ForecastScenarioResource::collection($scenarios)->response();
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
        ]);

        $scenario = $this->service->generateScenario(array_merge($request->all(), [
            'company_id' => $request->user()->company_id,
        ]));

        return (new ForecastScenarioResource($scenario))->response()->setStatusCode(201);
    }

    public function show(ForecastScenario $forecastScenario): JsonResponse
    {
        return (new ForecastScenarioResource($forecastScenario))->response();
    }

    public function run(ForecastScenario $forecastScenario): JsonResponse
    {
        GenerateForecastScenarioJob::dispatch($forecastScenario);

        return response()->json(['message' => 'Forecast generation queued.']);
    }
}
