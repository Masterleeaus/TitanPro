<?php

namespace Modules\CRMCore\Listeners;

use Modules\CRMCore\Actions\LogCRMActivity;
use Modules\CRMCore\Events\DealConvertedToProject;

class RecordDealConvertedToProject
{
    public function handle(DealConvertedToProject $event): void
    {
        app(LogCRMActivity::class)->handle('deal_converted_to_project', $event->deal, [
            'project_id' => $event->project->getKey(),
        ]);
    }
}
