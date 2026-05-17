<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Webhook;

use App\Extensions\TitanLeads\System\Models\OutboxApproval;
use App\Extensions\TitanLeads\System\Services\Outbox\OutboxService;
use Illuminate\Http\Request;

class OutboxApprovalWebhookController
{
    public function __invoke(string $token, Request $request, OutboxService $outbox)
    {
        $approval = OutboxApproval::query()->where('approval_token', $token)->first();
        if (!$approval) {
            return response('Not found', 404);
        }

        $status = $request->input('status', 'approved'); // approved|rejected

        if ($status === 'approved') {
            $outbox->markApproved($approval, $request->all());
            return response()->json(['status' => 'ok', 'decision' => 'approved']);
        }

        $approval->update([
            'approval_status' => 'rejected',
            'approval_payload' => $request->all(),
            'decided_at' => now(),
        ]);

        return response()->json(['status' => 'ok', 'decision' => 'rejected']);
    }
}
