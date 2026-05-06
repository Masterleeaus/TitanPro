<?php

namespace Modules\CleaningJobs\Services;

class CleaningJobUnifiedManagementService
{
    public function capabilities(): array
    {
        return [
            'cleaning_jobs' => [
                'work_orders',
                'appointments',
                'service_tasks',
                'parts_materials',
                'requests',
                'webhooks',
                'csv_import_export',
                'timesheets',
                'resource_planning',
                'capacity_planning',
                'financial_tracking',
                'kanban',
                'milestones',
                'subtasks',
                'comments',
                'files',
                'activity_logs',
            ],
        ];
    }
}
