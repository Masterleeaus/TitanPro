<?php
namespace Modules\TitanNexus\Tenancy\Resolvers;
final class NexusTenantResolver { public function resolve(array $context): ?string { return $context['tenant_id'] ?? null; } }

