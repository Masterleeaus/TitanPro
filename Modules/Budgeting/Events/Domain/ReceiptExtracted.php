<?php

declare(strict_types=1);

namespace Modules\Budgeting\Events\Domain;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Budgeting\Models\Receipt;

class ReceiptExtracted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly Receipt $model) {}
}
