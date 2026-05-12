<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\ClientFeedbackReceived;
use Modules\CleaningJobs\Models\WorkOrder;

class RecordClientFeedbackAction
{
    public function execute(WorkOrder $workOrder, int $rating, ?string $comment = null): WorkOrder
    {
        event(new ClientFeedbackReceived($workOrder, $rating, $comment));
        return $workOrder;
    }
}
