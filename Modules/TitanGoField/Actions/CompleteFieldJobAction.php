<?php

namespace Modules\TitanGoField\Actions;

use Modules\TitanGoField\Events\FieldJobCompleted;
use Modules\TitanGoField\Models\FieldJob;

class CompleteFieldJobAction
{
    public function execute(FieldJob $job, int $actorId, ?string $notes = null): FieldJob
    {
        $job->update([
            'status'       => FieldJob::STATUS_COMPLETED,
            'completed_at' => now(),
            'updated_by'   => $actorId,
            'notes'        => $notes ?? $job->notes,
        ]);

        event(new FieldJobCompleted($job, $actorId));

        return $job->refresh();
    }
}
