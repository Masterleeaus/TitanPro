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

        /** @var CallingAgentCredentialResolver $credentialResolver */
        $credentialResolver = app(CallingAgentCredentialResolver::class);
        $tokenCandidates = $credentialResolver->twilioAuthTokenCandidates();
        if ($tokenCandidates === []) {
            return response()->json(['error' => 'Twilio auth token is not configured'], 403);
        }

        $url = $request->fullUrl();
        $postData = $request->post();
        ksort($postData);
        $str = $url;
        foreach ($postData as $key => $val) {
            $str .= $key . $val;
        }

        foreach ($tokenCandidates as $candidate) {
            $signature = base64_encode(hash_hmac('sha1', $str, $candidate['token'], true));
            if (! hash_equals($signature, $expected)) {
                continue;
            }

            if ($candidate['company_id'] !== null) {
                TenantContext::setTenantId($candidate['company_id']);
            }

            return $next($request);
        }

        return response()->json(['error' => 'Invalid Twilio signature'], 403);
    }
}
