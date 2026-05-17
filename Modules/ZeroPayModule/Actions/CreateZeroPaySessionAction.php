<?php

namespace Modules\ZeroPayModule\Actions;

use Modules\ZeroPayModule\Data\ZeroPaySessionData;
use Modules\ZeroPayModule\Events\ZeroPaySessionCreated;
use Modules\ZeroPayModule\Models\ZeroPayGatewayLog;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class CreateZeroPaySessionAction
{
    public function execute(ZeroPaySessionData $data): ZeroPaySession
    {
        $record = ZeroPaySession::create($data->toArray());

        ZeroPayGatewayLog::create([
            'company_id' => $record->company_id,
            'session_id' => $record->id,
            'gateway' => $record->gateway,
            'action' => 'create_session',
            'request_payload' => $data->toArray(),
            'success' => true,
        ]);

        event(new ZeroPaySessionCreated(['session_id' => $record->id, 'session' => $record->toArray()]));

        return $record;
    }
}
