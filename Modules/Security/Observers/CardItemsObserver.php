<?php

namespace Modules\Security\Observers;

use Modules\Security\Entities\CardItems;

class CardItemsObserver
{
    public function saving(CardItems $unit)
    {
        if (company()) {
            $unit->company_id = company()->id;
        }
    }
}

