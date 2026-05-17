<?php

declare(strict_types=1);

namespace Modules\Budgeting\Services\Reimbursements;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Budgeting\Contracts\Services\ReimbursementsServiceContract;
use Modules\Budgeting\Events\Domain\ReimbursementBatchPaid;
use Modules\Budgeting\Models\Expense;
use Modules\Budgeting\Models\Reimbursement;
use Modules\Budgeting\Models\ReimbursementBatch;

class ReimbursementsService implements ReimbursementsServiceContract
{
    public function __construct(
        protected ReimbursementBatch $batchModel,
        protected Reimbursement $reimbursementModel,
    ) {}

    public function createBatch(int $companyId, array $expenseIds): ReimbursementBatch
    {
        return DB::transaction(function () use ($companyId, $expenseIds): ReimbursementBatch {
            $expenses = Expense::query()
                ->whereIn('id', $expenseIds)
                ->where('company_id', $companyId)
                ->where('status', 'approved')
                ->get();

            $total = $expenses->sum('amount');

            $batch = $this->batchModel->newQuery()->create([
                'company_id' => $companyId,
                'reference' => 'REIMB-' . strtoupper(Str::random(8)),
                'status' => 'draft',
                'total_amount' => $total,
            ]);

            foreach ($expenses as $expense) {
                $this->reimbursementModel->newQuery()->create([
                    'company_id' => $companyId,
                    'batch_id' => $batch->id,
                    'expense_id' => $expense->id,
                    'user_id' => $expense->submitted_by,
                    'amount' => $expense->amount,
                    'status' => 'pending',
                ]);
            }

            return $batch->refresh();
        });
    }

    public function approveBatch(ReimbursementBatch $batch): ReimbursementBatch
    {
        $batch->update(['status' => 'approved']);

        return $batch->refresh();
    }

    public function markPaid(ReimbursementBatch $batch): ReimbursementBatch
    {
        return DB::transaction(function () use ($batch): ReimbursementBatch {
            $batch->update(['status' => 'paid', 'paid_at' => now()]);

            $batch->reimbursements()->update(['status' => 'paid']);

            Expense::query()
                ->whereIn('id', $batch->reimbursements()->pluck('expense_id'))
                ->update(['status' => 'reimbursed']);

            event(new ReimbursementBatchPaid($batch));

            return $batch->refresh();
        });
    }
}
