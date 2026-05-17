<?php

namespace Modules\CallingAgent\Services;

use Modules\CallingAgent\Models\CallingAgentConfig;
use Modules\CallingAgent\Support\TenantContext;

class CallingAgentCredentialResolver
{
    /**
     * @var array<int, CallingAgentConfig|null>
     */
    private array $configCache = [];

    public function resolveConfig(?int $companyId = null): ?CallingAgentConfig
    {
        $companyId ??= TenantContext::id();

        if ($companyId === null) {
            return null;
        }

        if (array_key_exists($companyId, $this->configCache)) {
            return $this->configCache[$companyId];
        }

        return $this->configCache[$companyId] = CallingAgentConfig::query()
            ->withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->first();
    }

    public function twilioAccountSid(?int $companyId = null): ?string
    {
        return $this->resolveConfig($companyId)?->twilio_account_sid
            ?: config('services.twilio.sid');
    }

    public function twilioAuthToken(?int $companyId = null): ?string
    {
        return $this->resolveConfig($companyId)?->twilio_auth_token
            ?: config('services.twilio.token');
    }

    /**
     * @return list<array{company_id:int|null, token:string}>
     */
    public function twilioAuthTokenCandidates(): array
    {
        $candidates = CallingAgentConfig::query()
            ->withoutGlobalScopes()
            ->whereNotNull('twilio_auth_token')
            ->get(['company_id', 'twilio_auth_token'])
            ->map(function (CallingAgentConfig $config): array {
                $companyId = filter_var(
                    $config->company_id,
                    FILTER_VALIDATE_INT,
                    ['options' => ['min_range' => 1]],
                );

                return [
                    'company_id' => $companyId !== false ? $companyId : null,
                    'token' => (string) $config->twilio_auth_token,
                ];
            })
            ->filter(static fn (array $candidate): bool => $candidate['token'] !== '')
            ->values()
            ->all();

        $globalToken = config('services.twilio.token');
        if (is_string($globalToken) && $globalToken !== '') {
            $candidates[] = ['company_id' => null, 'token' => $globalToken];
        }

        return $candidates;
    }

    public function twilioFromNumber(?int $companyId = null): ?string
    {
        return $this->resolveConfig($companyId)?->twilio_from_number
            ?: config('services.twilio.from');
    }

    public function twilioWhatsappFrom(?int $companyId = null): ?string
    {
        return $this->resolveConfig($companyId)?->twilio_whatsapp_from
            ?: config('services.twilio.whatsapp_from')
            ?: $this->twilioFromNumber($companyId);
    }

    public function elevenLabsApiKey(?int $companyId = null): ?string
    {
        return $this->resolveConfig($companyId)?->elevenlabs_api_key
            ?: config('services.elevenlabs.key');
    }

    public function sipCredentials(?int $companyId = null): array
    {
        $config = $this->resolveConfig($companyId);

        if ($config === null) {
            return [];
        }

        $username = is_string($config->sip_username) && $config->sip_username !== '' ? $config->sip_username : null;
        $password = is_string($config->sip_password) && $config->sip_password !== '' ? $config->sip_password : null;
        $domain = is_string($config->sip_domain) && $config->sip_domain !== '' ? $config->sip_domain : null;

        if ($username === null || $password === null || $domain === null) {
            return [];
        }

        return [
            'username' => $username,
            'password' => $password,
            'domain' => $domain,
        ];
    }
}
