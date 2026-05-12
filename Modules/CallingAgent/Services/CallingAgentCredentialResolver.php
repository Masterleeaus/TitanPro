<?php

namespace Modules\CallingAgent\Services;

use Modules\CallingAgent\Models\CallingAgentConfig;
use Modules\CallingAgent\Support\TenantContext;

class CallingAgentCredentialResolver
{
    public function resolveConfig(?int $companyId = null): ?CallingAgentConfig
    {
        $companyId ??= TenantContext::id();

        if ($companyId === null) {
            return null;
        }

        return CallingAgentConfig::query()
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

        return array_filter([
            'username' => $config->sip_username,
            'password' => $config->sip_password,
            'domain' => $config->sip_domain,
        ], static fn ($value): bool => is_string($value) && $value !== '');
    }
}
