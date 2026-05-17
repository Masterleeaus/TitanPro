<?php

namespace Modules\Security\Observers;

use Modules\Security\Entities\TrInOutPermit;

class TrInOutPermitObserver
{

    public function saving(TrInOutPermit $unit)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $unit->company_id = company()->id;
        }
    }

}

