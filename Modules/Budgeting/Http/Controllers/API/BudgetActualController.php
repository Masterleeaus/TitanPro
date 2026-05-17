<?php

declare(strict_types=1);

namespace Modules\Budgeting\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Budgeting\Contracts\Services\BudgetActualsServiceContract;
use Modules\Budgeting\Http\Resources\BudgetActualResource;
use Modules\Budgeting\Models\BudgetActual;

class BudgetActualController extends Controller
{
    public function __construct(protected BudgetActualsServiceContract $service) {}

    public function index(Request $request): JsonResponse
    {
        $actuals = BudgetActual::query()
            ->where('company_id', $request->user()->company_id)
            ->latest()
            ->paginate(config('budgeting.pagination.per_page', 25));

        return BudgetActualResource::collection($actuals)->response();
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
            'planned_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $actual = $this->service->postActual(array_merge($request->all(), [
            'company_id' => $request->user()->company_id,
        ]));

        return (new BudgetActualResource($actual))->response()->setStatusCode(201);
    }

    public function show(BudgetActual $actual): JsonResponse
    {
        return (new BudgetActualResource($actual->load('variance')))->response();
    }
}
