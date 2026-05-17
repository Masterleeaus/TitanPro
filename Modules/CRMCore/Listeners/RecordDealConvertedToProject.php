<?php

namespace Modules\CRMCore\Listeners;

use Modules\CRMCore\Enums\PipelineSignal;
use Modules\CRMCore\Actions\LogCRMActivity;
use Modules\CRMCore\Events\DealConvertedToProject;

class RecordDealConvertedToProject
{
    public function handle(DealConvertedToProject $event): void
    {
        app(LogCRMActivity::class)->handle(PipelineSignal::DealConvertedToProject->value, $event->deal, [
            'project_id' => $event->project->getKey(),
        ]);
    }
}
