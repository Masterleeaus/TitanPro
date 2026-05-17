<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobAssigned;
use Modules\CleaningJobs\Models\WorkOrder;

class AssignTechnicianAction
{
    public function handle(WorkOrder $job, int $technicianId): WorkOrder
    {
        $job->update(['technician_id' => $technicianId]);
        JobAssigned::dispatch($job, 0, $technicianId);
        return $job;
    }
}
