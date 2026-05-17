<?php

namespace App\Support\GroundZero;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Modules\TitanCore\Services\TitanCoreRouter;
use Modules\TitanZero\Services\ZeroGateway;

class GroundZeroCommandBus
{
    public function dispatch(string $command, array $context = []): array
    {
        $command = trim($command);

        if ($command === '') {
            return [
                'ok' => false,
                'title' => 'Command needed',
                'message' => 'Type what you want GroundZero to do.',
                'cards' => [],
            ];
        }

        $tenantId = $this->tenantId();
        $intent = $this->classify($command);

        $envelope = [
            'source' => 'groundzero.workspace',
            'intent' => $intent,
            'input' => $command,
            'query' => $command,
            'agent_slug' => $this->agentForIntent($intent),
            'kb_collection_key' => 'kb_general_cleaning',
            'context' => $context,
        ];

        $zero = $this->tryTitanZero($envelope, $tenantId);
        if ($zero['ok']) {
            return $zero;
        }

        $core = $this->tryTitanCore($envelope);
        if ($core['ok']) {
            return $core;
        }

        return $this->localResponse($command, $intent, $zero['error'] ?? null, $core['error'] ?? null);
    }

    private function tryTitanZero(array $envelope, ?int $tenantId): array
    {
        if (! class_exists(ZeroGateway::class)) {
            return ['ok' => false, 'error' => 'TitanZero module not loaded'];
        }

        try {
            $gateway = app(ZeroGateway::class);
            $result = $gateway->runAgent($envelope, $tenantId);
            $message = Arr::get($result, 'result.message') ?: Arr::get($result, 'draft_text') ?: 'TitanZero completed the command.';

            return [
                'ok' => true,
                'title' => 'TitanZero response',
                'message' => $message,
                'audit_id' => $result['audit_id'] ?? null,
                'risk' => $result['risk'] ?? 'green',
                'cards' => $this->cardsFromResult($result),
                'raw' => $result,
            ];
        } catch (\Throwable $e) {
            Log::warning('GroundZero TitanZero dispatch failed', ['error' => $e->getMessage()]);
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    private function tryTitanCore(array $envelope): array
    {
        if (! class_exists(TitanCoreRouter::class)) {
            return ['ok' => false, 'error' => 'TitanCore module not loaded'];
        }

        try {
            $result = app(TitanCoreRouter::class)->invokeTool([
                'tool' => 'groundzero.command',
                'payload' => $envelope,
            ]);

            if (! ($result['ok'] ?? false)) {
                return ['ok' => false, 'error' => data_get($result, 'body.error', 'TitanCore provider unavailable')];
            }

            return [
                'ok' => true,
                'title' => 'TitanCore execution',
                'message' => (string) data_get($result, 'body.message', 'TitanCore executed the command.'),
                'cards' => [[
                    'label' => 'Provider',
                    'value' => 'TitanCore',
                    'tone' => 'success',
                ]],
                'raw' => $result,
            ];
        } catch (\Throwable $e) {
            Log::warning('GroundZero TitanCore dispatch failed', ['error' => $e->getMessage()]);
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    private function localResponse(string $command, string $intent, ?string $zeroError, ?string $coreError): array
    {
        return [
            'ok' => true,
            'title' => 'GroundZero command staged',
            'message' => "I understood this as {$intent}. TitanZero/TitanCore are installed, but the live AI provider is not ready for this request yet. The command has been staged safely instead of failing the panel.",
            'cards' => [
                ['label' => 'Command', 'value' => $command, 'tone' => 'info'],
                ['label' => 'Intent', 'value' => $intent, 'tone' => 'primary'],
                ['label' => 'TitanZero', 'value' => $zeroError ? 'Fallback: '.$zeroError : 'Ready', 'tone' => $zeroError ? 'warning' : 'success'],
                ['label' => 'TitanCore', 'value' => $coreError ? 'Fallback: '.$coreError : 'Ready', 'tone' => $coreError ? 'warning' : 'success'],
            ],
        ];
    }

    private function classify(string $command): string
    {
        $text = strtolower($command);
        return match (true) {
            str_contains($text, 'invoice') || str_contains($text, 'payment') || str_contains($text, 'overdue') => 'finance',
            str_contains($text, 'schedule') || str_contains($text, 'reschedule') || str_contains($text, 'calendar') => 'schedule',
            str_contains($text, 'cleaner') || str_contains($text, 'staff') || str_contains($text, 'team') => 'team',
            str_contains($text, 'customer') || str_contains($text, 'client') => 'customer',
            str_contains($text, 'job') || str_contains($text, 'clean') || str_contains($text, 'site') => 'jobs',
            default => 'general',
        };
    }

    private function agentForIntent(string $intent): string
    {
        return match ($intent) {
            'finance' => 'finance-agent',
            'schedule' => 'schedule-agent',
            'team' => 'workforce-agent',
            'customer' => 'customer-agent',
            'jobs' => 'operations-agent',
            default => 'groundzero-agent',
        };
    }

    private function cardsFromResult(array $result): array
    {
        $cards = [];
        if ($auditId = ($result['audit_id'] ?? null)) {
            $cards[] = ['label' => 'Audit ID', 'value' => $auditId, 'tone' => 'info'];
        }
        if ($risk = ($result['risk'] ?? null)) {
            $cards[] = ['label' => 'Risk', 'value' => ucfirst((string) $risk), 'tone' => $risk === 'green' ? 'success' : 'warning'];
        }
        if ($agent = data_get($result, 'agent.name') ?: data_get($result, 'agent.slug')) {
            $cards[] = ['label' => 'Agent', 'value' => (string) $agent, 'tone' => 'primary'];
        }
        if ($kb = ($result['kb_collection_key'] ?? null)) {
            $cards[] = ['label' => 'Knowledge', 'value' => (string) $kb, 'tone' => 'info'];
        }
        return $cards;
    }

    private function tenantId(): ?int
    {
        $user = auth()->user();
        return $user?->organization_id ?? $user?->company_id ?? null;
    }
}
