<?php

namespace Modules\CRMCore\Automation\Handlers;

use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Actions\CreateProjectFromDeal;

class CreateProjectForWonDealHandler
{
    public function handle(Deal $deal)
    {
        return app(CreateProjectFromDeal::class)->handle($deal);
    }
}
