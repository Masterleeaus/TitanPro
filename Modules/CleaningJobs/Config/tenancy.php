<?php

return [
    'tenant_column' => 'company_id',
    'legacy_tenant_column' => 'parent_id',
    'scoped_models' => [
        Modules\CleaningJobs\Models\WorkOrder::class,
        Modules\CleaningJobs\Models\WORequest::class,
        Modules\CleaningJobs\Models\WOServiceAppointment::class,
        Modules\CleaningJobs\Models\WOType::class,
        Modules\CleaningJobs\Models\ServicePart::class,
    ],
];
