<?php

namespace Modules\ZeroPayModule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\ZeroPayModule\Services\BankTransferMatchingService;

class ProcessBankDepositJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public array $rawDepositData) {}

    public function handle(BankTransferMatchingService $matchingService): void
    {
        $deposit = $matchingService->processIncomingDeposit($this->rawDepositData);

        if (in_array((string) $deposit->status, ['matched', 'rejected'], true)) {
            return;
        }

        $matchingService->match($deposit);
    }
}
