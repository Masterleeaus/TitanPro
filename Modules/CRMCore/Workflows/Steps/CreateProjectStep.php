<?php

namespace Modules\CRMCore\Workflows\Steps;

use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Actions\CreateProjectFromDeal;

class CreateProjectStep
{
    public function handle(Deal $deal)
    {
        return app(CreateProjectFromDeal::class)->handle($deal);
    }
}
