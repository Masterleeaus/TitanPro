<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\PhotoUploaded;
use Modules\CleaningJobs\Models\WorkOrder;

class UploadJobPhotoAction
{
    public function execute(WorkOrder $workOrder, string $photoPath, string $photoType = 'general'): WorkOrder
    {
        event(new PhotoUploaded($workOrder, $photoPath, $photoType));
        return $workOrder;
    }
}
