<?php

namespace Modules\CRMCore\Listeners;

use Modules\CRMCore\Actions\LogCRMActivity;
use Modules\CRMCore\Events\ContactCreated;

class LogContactCreatedActivity
{
    public function __construct(private readonly LogCRMActivity $logActivity) {}

    public function handle(ContactCreated $event): void
    {
        $this->logActivity->handle('contact.created', $event->contact, [
            'company_id' => $event->contact->company_id,
            'email'      => $event->contact->email_primary,
        ]);
    }
}
