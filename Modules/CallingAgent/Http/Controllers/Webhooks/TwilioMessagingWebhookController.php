<?php

namespace Modules\CallingAgent\Http\Controllers\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CallingAgent\AI\Agents\ReceptionistAgent;
use Modules\CallingAgent\Models\CallingAgentMessage;
use Modules\CallingAgent\Services\ReceptionistOrchestrator;
use Modules\CallingAgent\Services\TwilioChannelService;
use Modules\CallingAgent\Support\TenantContext;

class TwilioMessagingWebhookController extends Controller
{
    public function __construct(
        private TwilioChannelService $twilio,
        private ReceptionistAgent $agent,
        private ReceptionistOrchestrator $orchestrator
    ) {}

    public function incoming(Request $request)
    {
        TenantContext::setTenantId(TenantContext::id($request->all()));

        $messageSid = (string) ($request->input('MessageSid') ?: $request->input('SmsSid', ''));
        $from       = (string) $request->input('From', '');
        $to         = (string) $request->input('To', '');
        $body       = (string) $request->input('Body', '');
        $channel    = str_starts_with($from, 'whatsapp:') ? 'whatsapp' : 'sms';
        $lookupTo   = preg_replace('/^whatsapp:/i', '', $to) ?: $to;
        $agent      = $this->orchestrator->resolveByNumber($lookupTo);
        $tenantId   = $agent?->tenant_id ?? TenantContext::id();

        // Idempotency: skip duplicate deliveries
        if ($messageSid && $this->orchestrator->isDuplicate('msg:' . $messageSid, 'twilio')) {
            return response('', 204);
        }

        // Persist inbound message
        CallingAgentMessage::create([
            'tenant_id'   => $tenantId,
            'calling_agent_id' => $agent?->id,
            'provider'    => 'twilio',
            'channel'     => $channel,
            'message_sid' => $messageSid ?: null,
            'from'        => $from,
            'to'          => $to,
            'body'        => $body,
            'direction'   => 'inbound',
            'status'      => 'received',
            'metadata'    => $request->all(),
        ]);

        // Enrich context with caller profile memory
        $context = [];
        $callerPhone = $channel === 'whatsapp'
            ? preg_replace('/^whatsapp:/i', '', $from)
            : $from;

        $profile = $this->orchestrator->recallCallerProfile($callerPhone);
        if ($profile) {
            $context['caller_profile'] = $profile->toArray();
        }

        // Generate AI reply
        $reply = $this->agent->respond($body, $context);

        // Send reply on the same channel
        if ($channel === 'whatsapp') {
            $this->twilio->sendWhatsapp($from, $reply, $to);
        } else {
            $this->twilio->sendSms($from, $reply, $to);
        }

        // Persist outbound message
        CallingAgentMessage::create([
            'tenant_id' => $tenantId,
            'calling_agent_id' => $agent?->id,
            'provider'  => 'twilio',
            'channel'   => $channel,
            'from'      => $to,
            'to'        => $from,
            'body'      => $reply,
            'direction' => 'outbound',
            'status'    => 'sent',
        ]);

        return response('', 204);
    }
}
