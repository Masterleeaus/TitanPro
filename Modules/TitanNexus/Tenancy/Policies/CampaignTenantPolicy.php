<?php
namespace Modules\TitanNexus\Tenancy\Policies;
final class CampaignTenantPolicy { public function canAccess(string $tenantId, array $campaign): bool { return ($campaign['tenant_id'] ?? null) === $tenantId; } }

