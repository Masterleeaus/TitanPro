<?php

namespace Modules\CRMCore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\CRMCore\Models\Contact;

class ContactCreated
{
    use Dispatchable;

    public function __construct(public Contact $contact)
    {
    }
}
