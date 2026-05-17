<?php

declare(strict_types=1);

namespace Modules\Budgeting\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Budgeting\Contracts\Services\ExpensesServiceContract;
use Modules\Budgeting\Http\Requests\ApproveExpenseRequest;
use Modules\Budgeting\Http\Requests\StoreExpenseRequest;
use Modules\Budgeting\Http\Resources\ExpenseResource;
use Modules\Budgeting\Models\Expense;

class ExpenseController extends Controller
{
    public function __construct(protected ExpensesServiceContract $service) {}

    public function index(Request $request): JsonResponse
    {
        $expenses = $this->service->listForCompany(
            (int) $request->user()->company_id,
            $request->only(['status', 'submitted_by'])
        );

        return ExpenseResource::collection($expenses)->response();
    }

    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $expense = $this->service->submit(array_merge($request->validated(), [
            'company_id' => $request->user()->company_id,
            'submitted_by' => $request->user()->id,
        ]));

        return (new ExpenseResource($expense))->response()->setStatusCode(201);
    }

    public function show(Expense $expense): JsonResponse
    {
        return (new ExpenseResource($expense->load(['submittedBy', 'category', 'receipt'])))->response();
    }

    public function update(StoreExpenseRequest $request, Expense $expense): JsonResponse
    {
        $expense->update($request->validated());

        return (new ExpenseResource($expense->refresh()))->response();
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $expense->delete();

        return response()->json(null, 204);
    }

    public function approve(ApproveExpenseRequest $request, Expense $expense): JsonResponse
    {
        $approved = $this->service->approve($expense, (int) $request->user()->id, $request->input('notes'));

        return (new ExpenseResource($approved))->response();
    }

    public function reject(ApproveExpenseRequest $request, Expense $expense): JsonResponse
    {
        $rejected = $this->service->reject($expense, (int) $request->user()->id, $request->input('notes'));

        return (new ExpenseResource($rejected))->response();
    }
}
