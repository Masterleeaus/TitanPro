<?php

namespace Modules\CRMCore\Jobs;

use Modules\CRMCore\Models\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\CRMCore\Actions\CreateProjectFromDeal;

class ConvertDealToProjectJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $dealId)
    {
    }

    public function handle(): void
    {
        $deal = Deal::find($this->dealId);

        if ($deal) {
            app(CreateProjectFromDeal::class)->handle($deal);
        }
    }
}
