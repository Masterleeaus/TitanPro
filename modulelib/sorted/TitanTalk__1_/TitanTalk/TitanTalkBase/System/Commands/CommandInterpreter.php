<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Commands;

use App\Extensions\MarketingBot\System\Enums\ConversationIntent;

class CommandInterpreter
{
    /**
     * @param array<string,mixed> $classification
     * @return array<string,mixed>
     */
    public function interpret(string $message, array $classification = []): array
    {
        $intent = $classification['intent'] ?? ConversationIntent::GENERAL;
        $entities = (array) ($classification['entities'] ?? []);
        $confidence = (float) ($classification['confidence'] ?? 0.0);
        $goal = (string) ($classification['goal'] ?? 'answer_question');

        $command = $this->commandForIntent($intent);
        $params = $this->parametersForCommand($command, $message, $entities);
        $missing = $this->missingParameters($command, $params);

        return [
            'command' => $command,
            'params' => $params,
            'confidence' => $confidence,
            'goal' => $goal,
            'missing' => $missing,
            'requires_confirmation' => $this->requiresConfirmation($command, $confidence),
            'is_actionable' => $command !== 'knowledge.answer' && $missing === [],
        ];
    }

    private function commandForIntent(mixed $intent): string
    {
        if (! $intent instanceof ConversationIntent) {
            return 'knowledge.answer';
        }

        return match ($intent) {
            ConversationIntent::BOOKING => 'booking.create',
            ConversationIntent::QUOTE => 'quote.prepare',
            ConversationIntent::SUPPORT => 'ticket.create',
            ConversationIntent::COMPLAINT => 'service.issue.log',
            ConversationIntent::INVOICE => 'invoice.lookup',
            ConversationIntent::RESCHEDULE => 'booking.reschedule',
            ConversationIntent::CANCEL => 'booking.cancel',
            ConversationIntent::HUMAN_HANDOFF => 'human.handoff',
            ConversationIntent::GENERAL => 'knowledge.answer',
        };
    }

    /**
     * @param array<string,mixed> $entities
     * @return array<string,mixed>
     */
    private function parametersForCommand(string $command, string $message, array $entities): array
    {
        $params = $entities;
        $params['source_text'] = trim($message);

        if (! isset($params['service']) && preg_match('/(clean(?:ing)?|gardening|repair|quote|service)/i', $message, $m) === 1) {
            $params['service'] = strtolower($m[1]);
        }

        if (! isset($params['reference']) && preg_match('/(ref|booking|ticket|job|invoice)[\s#:-]*([a-z0-9-]+)/i', $message, $m) === 1) {
            $params['reference'] = $m[2];
        }

        if (! isset($params['reason']) && preg_match('/(cancel|complaint|human|operator|person|agent|invoice)/i', $message, $m) === 1) {
            $params['reason'] = strtolower($m[1]);
        }

        return $params;
    }

    /**
     * @param array<string,mixed> $params
     * @return list<string>
     */
    private function missingParameters(string $command, array $params): array
    {
        $required = match ($command) {
            'booking.create' => ['day_hint'],
            'quote.prepare' => [],
            'ticket.create' => ['source_text'],
            'service.issue.log' => ['source_text'],
            'invoice.lookup' => [],
            'booking.reschedule' => ['reference', 'day_hint'],
            'booking.cancel' => ['reference'],
            'human.handoff' => [],
            default => [],
        };

        $missing = [];
        foreach ($required as $name) {
            if (! array_key_exists($name, $params) || $params[$name] === null || $params[$name] === '') {
                $missing[] = $name;
            }
        }

        return $missing;
    }

    private function requiresConfirmation(string $command, float $confidence): bool
    {
        if ($confidence < (float) config('titantalk.command_confidence_threshold', 0.72)) {
            return true;
        }

        return in_array($command, ['booking.cancel', 'booking.reschedule'], true);
    }
}
