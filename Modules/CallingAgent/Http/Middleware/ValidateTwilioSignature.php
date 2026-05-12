<?php

namespace Modules\CallingAgent\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\CallingAgent\Services\CallingAgentCredentialResolver;
use Modules\CallingAgent\Support\TenantContext;
use Symfony\Component\HttpFoundation\Response;

class ValidateTwilioSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('calling-agent.skip_twilio_validation', env('CALLING_AGENT_SKIP_TWILIO_VALIDATION', false))) {
            return $next($request);
        }

        $expected = (string) $request->header('X-Twilio-Signature', '');
        if ($expected === '') {
            return response()->json(['error' => 'Missing Twilio signature'], 403);
        }

        $tenantResolutionPayload = [
            'To' => $request->input('To'),
            'From' => $request->input('From'),
            'CallSid' => $request->input('CallSid'),
            'MessageSid' => $request->input('MessageSid'),
            'SmsSid' => $request->input('SmsSid'),
            'call_sid' => $request->input('call_sid'),
            'message_sid' => $request->input('message_sid'),
            'calling_agent_id' => $request->input('calling_agent_id'),
            'agent_id' => $request->input('agent_id'),
        ];

        $tenantId = TenantContext::id($tenantResolutionPayload);
        $authToken = app(CallingAgentCredentialResolver::class)->twilioAuthToken($tenantId);
        if (! is_string($authToken) || $authToken === '') {
            return response()->json(['error' => 'Twilio auth token is not configured'], 403);
        }

        $url = $request->fullUrl();
        $postData = $request->post();
        ksort($postData);
        $str = $url;
        foreach ($postData as $key => $val) {
            $str .= $key . $val;
        }

        $signature = base64_encode(hash_hmac('sha1', $str, $authToken, true));
        if (!hash_equals($signature, $expected)) {
            return response()->json(['error' => 'Invalid Twilio signature'], 403);
        }

        return $next($request);
    }
}
