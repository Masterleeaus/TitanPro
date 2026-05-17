<?php

namespace Modules\TitanEchoAssist\Services;

class TitanGoVoiceActionMapper
{
    private ?string $matchedPhrase = null;

    public function mapPhraseToAction(string $transcript): ?string
    {
        $this->matchedPhrase = null;

        $normalizedTranscript = $this->normalize($transcript);
        if ($normalizedTranscript === '') {
            return null;
        }

        $voiceActions = (array) config('titango.voice_actions', $this->defaultVoiceActions());

        // Exact and containment match first.
        foreach ($voiceActions as $actionKey => $phrases) {
            foreach ((array) $phrases as $phrase) {
                $normalizedPhrase = $this->normalize((string) $phrase);
                if ($normalizedPhrase === '') {
                    continue;
                }

                if ($normalizedTranscript === $normalizedPhrase || str_contains($normalizedTranscript, $normalizedPhrase)) {
                    $this->matchedPhrase = $normalizedPhrase;

                    return (string) $actionKey;
                }
            }
        }

        // Fuzzy match fallback.
        $bestAction = null;
        $bestPhrase = null;
        $bestScore = 0.0;

        foreach ($voiceActions as $actionKey => $phrases) {
            foreach ((array) $phrases as $phrase) {
                $normalizedPhrase = $this->normalize((string) $phrase);
                if ($normalizedPhrase === '') {
                    continue;
                }

                similar_text($normalizedTranscript, $normalizedPhrase, $percent);

                $distance = levenshtein($normalizedTranscript, $normalizedPhrase);
                $maxLen = max(strlen($normalizedTranscript), strlen($normalizedPhrase), 1);
                $distanceScore = max(0, (1 - ($distance / $maxLen)) * 100);

                $score = max($percent, $distanceScore);
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestAction = (string) $actionKey;
                    $bestPhrase = $normalizedPhrase;
                }
            }
        }

        if ($bestAction !== null && $bestScore >= 65.0) {
            $this->matchedPhrase = $bestPhrase;

            return $bestAction;
        }

        return null;
    }

    public function matchedPhrase(): ?string
    {
        return $this->matchedPhrase;
    }

    private function normalize(string $value): string
    {
        $normalized = strtolower(trim($value));
        $normalized = preg_replace('/[^a-z0-9\s]/', ' ', $normalized) ?? '';

        return trim(preg_replace('/\s+/', ' ', $normalized) ?? '');
    }

    private function defaultVoiceActions(): array
    {
        return [
            'site_diary.create' => ['create site diary', 'add diary entry', 'site note'],
            'compliance.scan' => ['scan compliance', 'run compliance check', 'check compliance'],
            'job.summary' => ['summarise job', 'summarize job', 'job summary', 'what is this job'],
            'quote.risk_scan' => ['quote risk scan', 'risk assessment', 'scan risks'],
        ];
    }
}
