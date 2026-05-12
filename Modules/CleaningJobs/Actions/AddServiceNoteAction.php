<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\ServiceNoteAdded;
use Modules\CleaningJobs\Models\WorkOrder;

class AddServiceNoteAction
{
    public function execute(WorkOrder $workOrder, string $note): WorkOrder
    {
        event(new ServiceNoteAdded($workOrder, $note));
        return $workOrder;
    }
}
