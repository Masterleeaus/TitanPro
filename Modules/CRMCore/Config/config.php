<?php

return [
    'name' => 'CRMCore',
    'mode' => 'fullfat_bridge',
    'original_source' => 'Modules/CRMCore/_original_source',
    'host_customer_model' => 'App\\Models\\Customer',
    'host_user_customer_model' => 'App\\Models\\User',
    'host_lead_model' => 'Modules\\CRMCore\\Models\\Lead',
    'host_deal_model' => 'Modules\\CRMCore\\Models\\Deal',
    'host_job_model' => 'App\\Models\\Job',
    'host_service_model' => 'App\\Models\\Item'
];
