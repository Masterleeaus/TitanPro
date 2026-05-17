<?php

namespace Modules\TitanGoField\Actions;

use Modules\TitanGoField\Events\FieldJobUpdated;
use Modules\TitanGoField\Models\FieldJob;

class CheckInFieldJobAction
{
    public function execute(FieldJob $job, int $actorId, ?array $gpsCoords = null): FieldJob
    {
        $meta = $job->meta ?? [];
        $meta['check_in'] = [
            'at'     => now()->toIso8601String(),
            'actor'  => $actorId,
            'coords' => $gpsCoords,
        ];

        $job->update([
            'status'     => FieldJob::STATUS_IN_PROGRESS,
            'started_at' => $job->started_at ?? now(),
            'meta'       => $meta,
            'updated_by' => $actorId,
        ]);

        event(new FieldJobUpdated($job, $actorId, ['action' => 'check_in']));

        return $job->refresh();
    }
}
