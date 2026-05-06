<?php

namespace App\Services;

use App\Models\TitanModuleAuditLog;
use Illuminate\Contracts\Auth\Guard;

/**
 * Records append-only audit entries for module lifecycle actions.
 *
 * Supported actions: sync | enable | disable
 */
class ModuleAuditLogger
{
    public function __construct(private readonly Guard $auth) {}

    /**
     * Derive the actor label: numeric user_id when authenticated, or "system".
     */
    private function actor(): string
    {
        $user = $this->auth->user();

        return $user ? (string) $user->getAuthIdentifier() : 'system';
    }

    public function logSync(string $module, string $outcome = 'success', array $context = []): void
    {
        $this->write('sync', $module, $outcome, $context);
    }

    public function logEnable(string $module, string $outcome = 'success', array $context = []): void
    {
        $this->write('enable', $module, $outcome, $context);
    }

    public function logDisable(string $module, string $outcome = 'success', array $context = []): void
    {
        $this->write('disable', $module, $outcome, $context);
    }

    private function write(string $action, string $module, string $outcome, array $context): void
    {
        TitanModuleAuditLog::create([
            'actor' => $this->actor(),
            'action' => $action,
            'module' => $module,
            'outcome' => $outcome,
            'context' => $context ?: null,
        ]);
    }
}
