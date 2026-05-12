<?php

namespace Modules\TitanGoField\Actions;

use Modules\TitanGoField\Events\FieldJobUpdated;
use Modules\TitanGoField\Models\FieldJob;

class UpdateFieldJobStatusAction
{
    public function execute(FieldJob $job, string $status, int $actorId): FieldJob
    {
        $previous = $job->status;

        $job->update([
            'status'     => $status,
            'updated_by' => $actorId,
            'started_at' => $status === FieldJob::STATUS_IN_PROGRESS && !$job->started_at
                ? now()
                : $job->started_at,
        ]);

        event(new FieldJobUpdated($job, $actorId, ['status' => ['from' => $previous, 'to' => $status]]));

        return $job->refresh();
    }
}
