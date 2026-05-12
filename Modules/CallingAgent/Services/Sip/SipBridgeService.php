<?php
namespace Modules\CallingAgent\Services\Sip;

use Modules\CallingAgent\Services\CallingAgentCredentialResolver;
use Modules\CallingAgent\Support\TenantContext;

final class SipBridgeService
{
    public function __construct(private readonly CallingAgentCredentialResolver $credentialResolver = new CallingAgentCredentialResolver()) {}

    public function normalizeUri(string $destination): string
    {
        return str_starts_with($destination, 'sip:') ? $destination : 'sip:' . ltrim($destination);
    }

    public function bridgePlan(string $callSid, string $destination, array $options = []): array
    {
        if (! array_key_exists('credentials', $options)) {
            $credentials = $this->credentialResolver->sipCredentials(TenantContext::id());
            if ($credentials !== []) {
                $options['credentials'] = $credentials;
            }
        }

        return ['call_sid' => $callSid, 'sip_uri' => $this->normalizeUri($destination), 'options' => $options, 'mode' => 'sip_bridge'];
    }
}
