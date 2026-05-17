<?php

namespace Modules\TitanGoField\Listeners;

use Modules\TitanGoField\Jobs\DispatchFieldJobWebhookJob;

class SendFieldJobWebhook
{
    public function handle(object $event): void
    {
        $setting = \Modules\TitanGoField\Models\FsmSetting::forCompany($event->fieldJob->company_id);

        if (empty($setting->webhook_url)) {
            return;
        }

        DispatchFieldJobWebhookJob::dispatch(
            $event->fieldJob->id,
            $event->fieldJob->company_id,
            $event->topic(),
            $setting->webhook_url,
        );
    }
}
