<?php

declare(strict_types=1);

namespace Modules\Budgeting\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Budgeting\Contracts\Services\ReceiptsServiceContract;
use Modules\Budgeting\Http\Requests\UploadReceiptRequest;
use Modules\Budgeting\Http\Resources\ReceiptResource;
use Modules\Budgeting\Models\Receipt;

class ReceiptController extends Controller
{
    public function __construct(protected ReceiptsServiceContract $service) {}

    public function index(Request $request): JsonResponse
    {
        $receipts = Receipt::query()
            ->where('company_id', $request->user()->company_id)
            ->latest()
            ->paginate(config('budgeting.pagination.per_page', 25));

        return ReceiptResource::collection($receipts)->response();
    }

    public function store(UploadReceiptRequest $request): JsonResponse
    {
        $receipt = $this->service->upload(
            (int) $request->user()->company_id,
            $request->file('file'),
            $request->input('expense_id')
        );

        return (new ReceiptResource($receipt))->response()->setStatusCode(201);
    }

    public function show(Receipt $receipt): JsonResponse
    {
        return (new ReceiptResource($receipt->load('expense')))->response();
    }

    public function destroy(Receipt $receipt): JsonResponse
    {
        $receipt->delete();

        return response()->json(null, 204);
    }
}
