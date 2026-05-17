<?php

declare(strict_types=1);

namespace Modules\Budgeting\Services\Receipts;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Budgeting\Contracts\Services\ReceiptsServiceContract;
use Modules\Budgeting\Events\Domain\ReceiptExtracted;
use Modules\Budgeting\Jobs\Queued\RunReceiptOcrJob;
use Modules\Budgeting\Models\Receipt;

class ReceiptsService implements ReceiptsServiceContract
{
    public function __construct(protected Receipt $model) {}

    public function upload(int $companyId, UploadedFile $file, ?int $expenseId = null): Receipt
    {
        $path = Storage::disk('local')->putFile("receipts/{$companyId}", $file);

        $receipt = $this->model->newQuery()->create([
            'company_id' => $companyId,
            'expense_id' => $expenseId,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
            'ocr_status' => 'pending',
        ]);

        event(new ReceiptExtracted($receipt));

        return $receipt;
    }

    public function triggerOcr(Receipt $receipt): void
    {
        $receipt->update(['ocr_status' => 'processing']);
        RunReceiptOcrJob::dispatch($receipt);
    }

    public function matchToExpense(Receipt $receipt, int $expenseId): Receipt
    {
        $receipt->update(['expense_id' => $expenseId]);

        return $receipt->refresh();
    }
}
