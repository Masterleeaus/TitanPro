<?php
namespace Modules\TitanNexus\Workflows\Guards;
final class HumanApprovalGuard { public function allows(array $draft): bool { return ($draft['approved'] ?? false) === true; } }

