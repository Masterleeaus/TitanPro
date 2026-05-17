<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Safety;

class CommandSafetyService
{
    /**
     * @param array<string,mixed> $command
     * @param array<string,mixed> $classification
     * @return array<string,mixed>
     */
    public function assess(array $command, array $classification = []): array
    {
        $name = (string) ($command['command'] ?? 'knowledge.answer');
        $confidence = (float) ($command['confidence'] ?? ($classification['confidence'] ?? 0.0));
        $missing = (array) ($command['missing'] ?? []);

        $risk = $this->riskForCommand($name);
        $threshold = $this->thresholdForRisk($risk);
        $blocked = in_array($name, (array) config('titantalk.blocked_commands', []), true);
        $requiresApproval = $risk === 'high' || (bool) ($command['requires_confirmation'] ?? false);
        $shouldClarify = $missing !== [] || $confidence < $threshold;

        return [
            'command' => $name,
            'risk' => $risk,
            'confidence' => $confidence,
            'confidence_threshold' => $threshold,
            'missing' => $missing,
            'blocked' => $blocked,
            'requires_approval' => $requiresApproval,
            'should_clarify' => $shouldClarify,
            'safe_to_autorun' => ! $blocked && ! $requiresApproval && ! $shouldClarify,
            'reason' => $this->reason($blocked, $requiresApproval, $shouldClarify, $missing),
        ];
    }

    private function riskForCommand(string $name): string
    {
        $map = (array) config('titantalk.command_risk_map', []);
        return (string) ($map[$name] ?? 'low');
    }

    private function thresholdForRisk(string $risk): float
    {
        return match ($risk) {
            'high' => 0.9,
            'medium' => 0.78,
            default => (float) config('titantalk.command_confidence_threshold', 0.72),
        };
    }

    /** @param list<string> $missing */
    private function reason(bool $blocked, bool $requiresApproval, bool $shouldClarify, array $missing): string
    {
        if ($blocked) {
            return 'Blocked by TitanTalk safety policy.';
        }
        if ($missing !== []) {
            return 'Missing required details: ' . implode(', ', $missing) . '.';
        }
        if ($requiresApproval) {
            return 'This action needs confirmation or operator approval.';
        }
        if ($shouldClarify) {
            return 'Confidence is below the safe automation threshold.';
        }

        return 'Safe for assistant-guided processing.';
    }
}
