<?php

namespace Modules\Security\API\SDK;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SecurityClient
{
    public function __construct(
        private readonly ?string $baseUrl = null,
        private readonly ?string $token = null,
    ) {
    }

    public function http(): PendingRequest
    {
        $request = Http::baseUrl(rtrim($this->baseUrl ?? config('security_integrations.api.base_url', ''), '/'));

        $token = $this->token ?? config('security_integrations.api.token');
        if ($token) {
            $request = $request->withToken($token);
        }

        return $request->acceptJson();
    }

    public function status(): array
    {
        return $this->http()->get('/api/security/status')->json() ?? [];
    }

    public function health(): array
    {
        return $this->http()->get('/api/security/health')->json() ?? [];
    }
}
