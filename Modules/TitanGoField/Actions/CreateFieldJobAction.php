<?php

namespace Modules\TitanGoField\Actions;

use Modules\TitanGoField\Events\FieldJobCreated;
use Modules\TitanGoField\Models\FieldJob;
use Modules\TitanGoField\Support\DTOs\CreateFieldJobData;

class CreateFieldJobAction
{
    public function execute(CreateFieldJobData $data): FieldJob
    {
        $job = FieldJob::create([
            'company_id'     => $data->companyId,
            'created_by'     => $data->actorId,
            'type_id'        => $data->typeId,
            'client_id'      => $data->clientId,
            'technician_id'  => $data->technicianId,
            'status'         => FieldJob::STATUS_PENDING,
            'priority'       => $data->priority ?? 'normal',
            'description'    => $data->description,
            'notes'          => $data->notes,
            'scheduled_start' => $data->scheduledStart,
            'scheduled_end'  => $data->scheduledEnd,
            'due_at'         => $data->dueAt,
        ]);

        event(new FieldJobCreated($job, $data->actorId));

        return $job;
    }
}
