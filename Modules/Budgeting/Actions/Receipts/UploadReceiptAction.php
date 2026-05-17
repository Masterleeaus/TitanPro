<?php

declare(strict_types=1);

namespace Modules\Budgeting\Actions\Receipts;

use Illuminate\Http\UploadedFile;
use Modules\Budgeting\Contracts\Services\ReceiptsServiceContract;
use Modules\Budgeting\Models\Receipt;

class UploadReceiptAction
{
    public function __construct(protected ReceiptsServiceContract $service) {}

    public function handle(int $companyId, UploadedFile $file, ?int $expenseId = null): Receipt
    {
        return $this->service->upload($companyId, $file, $expenseId);
    }
}
