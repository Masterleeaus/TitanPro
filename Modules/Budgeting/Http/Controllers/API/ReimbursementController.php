<?php

declare(strict_types=1);

namespace Modules\Budgeting\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Budgeting\Contracts\Services\ReimbursementsServiceContract;
use Modules\Budgeting\Http\Requests\CreateReimbursementBatchRequest;
use Modules\Budgeting\Http\Resources\ReimbursementResource;
use Modules\Budgeting\Models\ReimbursementBatch;

class ReimbursementController extends Controller
{
    public function __construct(protected ReimbursementsServiceContract $service) {}

    public function index(Request $request): JsonResponse
    {
        $batches = ReimbursementBatch::query()
            ->where('company_id', $request->user()->company_id)
            ->latest()
            ->paginate(config('budgeting.pagination.per_page', 25));

        return ReimbursementResource::collection($batches)->response();
    }

    public function store(CreateReimbursementBatchRequest $request): JsonResponse
    {
        $batch = $this->service->createBatch(
            (int) $request->user()->company_id,
            $request->validated('expense_ids')
        );

        return (new ReimbursementResource($batch))->response()->setStatusCode(201);
    }

    public function show(ReimbursementBatch $reimbursementBatch): JsonResponse
    {
        return (new ReimbursementResource($reimbursementBatch->load('reimbursements')))->response();
    }

    public function approve(ReimbursementBatch $reimbursementBatch): JsonResponse
    {
        $batch = $this->service->approveBatch($reimbursementBatch);

        return (new ReimbursementResource($batch))->response();
    }

    public function markPaid(ReimbursementBatch $reimbursementBatch): JsonResponse
    {
        $batch = $this->service->markPaid($reimbursementBatch);

        return (new ReimbursementResource($batch))->response();
    }
}
