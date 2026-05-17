<?php

namespace Modules\Security\Observers;

use Modules\Security\Entities\WorkPermitsFile;

class WorkPermitsFileObserver
{

    public function saving(WorkPermitsFile $unit)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $unit->company_id = company()->id;
        }
    }

}

