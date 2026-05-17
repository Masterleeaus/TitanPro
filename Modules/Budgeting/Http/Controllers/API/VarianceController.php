<?php

declare(strict_types=1);

namespace Modules\Budgeting\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Budgeting\Http\Resources\VarianceResource;
use Modules\Budgeting\Models\BudgetVariance;

class VarianceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $variances = BudgetVariance::query()
            ->where('company_id', $request->user()->company_id)
            ->with('actual')
            ->latest()
            ->paginate(config('budgeting.pagination.per_page', 25));

        return VarianceResource::collection($variances)->response();
    }

    public function show(BudgetVariance $variance): JsonResponse
    {
        return (new VarianceResource($variance->load('actual')))->response();
    }
}
