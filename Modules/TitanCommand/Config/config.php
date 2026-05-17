<?php

return [
    /*
     |--------------------------------------------------------------------------
     | TitanCommand Configuration
     |--------------------------------------------------------------------------
     */

    'name' => 'TitanCommand',

    /*
     | Default job priorities offered in the UI.
     */
    'priorities' => ['low', 'normal', 'high', 'urgent'],

    /*
     | Default job lifecycle statuses.
     */
    'statuses' => ['open', 'assigned', 'in_progress', 'checklist_complete', 'inspection', 'closed', 'cancelled'],

    /*
     | Evidence storage disk (uses Laravel filesystem config key).
     */
    'evidence_disk' => env('TITANCOMMAND_EVIDENCE_DISK', 'local'),
];
