<?php

declare(strict_types=1);

namespace Modules\Budgeting\Contracts\Services;

use Illuminate\Http\UploadedFile;
use Modules\Budgeting\Models\Receipt;

interface ReceiptsServiceContract
{
    public function upload(int $companyId, UploadedFile $file, ?int $expenseId = null): Receipt;

    public function triggerOcr(Receipt $receipt): void;

    public function matchToExpense(Receipt $receipt, int $expenseId): Receipt;
}
