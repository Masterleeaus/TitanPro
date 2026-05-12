<?php

namespace Modules\CallingAgent\AI\Tools;

use Modules\CallingAgent\Models\CallingAgentCallerProfile;
use Modules\CallingAgent\Support\TenantContext;

final class CallerMemoryTool
{
    public function execute(array $input): array
    {
        $phone = TenantContext::normalizeAddress($input['phone'] ?? null);
        $email = is_string($input['email'] ?? null) ? trim((string) $input['email']) : null;
        $companyId = isset($input['company_id']) && is_numeric($input['company_id'])
            ? (int) $input['company_id']
            : TenantContext::id();

        if ($phone === null && ($email === null || $email === '')) {
            return [
                'found' => false,
                'company_id' => $companyId,
                'profile' => null,
            ];
        }

        $profileQuery = CallingAgentCallerProfile::query()->withoutGlobalScopes();

        if ($companyId !== null) {
            $profileQuery->where('tenant_id', $companyId);
        }

        $profileQuery->where(function ($query) use ($phone, $email): void {
            if ($phone !== null) {
                $query->where('phone', $phone);
            }

            if ($email !== null && $email !== '') {
                if ($phone !== null) {
                    $query->orWhere('email', $email);
                } else {
                    $query->where('email', $email);
                }
            }
        });

        $profile = $profileQuery->first();

        return [
            'found' => $profile !== null,
            'company_id' => $companyId,
            'profile' => $profile?->toArray(),
        ];
    }
}
