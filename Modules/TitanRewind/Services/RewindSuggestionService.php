<?php

namespace Modules\TitanRewind\Services;

use Illuminate\Support\Str;
use Modules\TitanRewind\Models\RewindEvent;
use Modules\TitanRewind\Models\RewindFix;

class RewindSuggestionService
{
    public function __construct(private readonly TitanPulseBridge $bridge) {}

    public function suggestForEvent(RewindEvent $event): ?RewindFix
    {
        if ($event->entity_type === null || $event->event_type === null) {
            return null;
        }

        $minMatches = (int) config('titan-rewind.suggestion_similarity.min_matches', 1);

        $matchCount = RewindEvent::query()
            ->where('company_id', $event->company_id)
            ->where('entity_type', $event->entity_type)
            ->where('event_type', $event->event_type)
            ->where('case_id', '!=', $event->case_id)
            ->count();

        if ($matchCount < $minMatches) {
            return null;
        }

        $existing = RewindFix::query()
            ->where('case_id', $event->case_id)
            ->where('proposed_by_type', 'ai')
            ->first();

        if ($existing) {
            return $existing;
        }

        $fix = RewindFix::query()->create([
            'company_id' => $event->company_id,
            'case_id' => $event->case_id,
            'fix_type' => 'metadata_update',
            'proposed_by_type' => 'ai',
            'requires_confirmation' => true,
            'status' => 'proposed',
            'proposal_json' => [
                'summary' => 'AI corrective suggestion generated from similar rewind events.',
                'matches' => $matchCount,
                'target_table' => 'titan_rewind_cases',
                'target_id' => $event->case_id,
                'meta_key' => 'ai_suggestion',
                'meta_value' => [
                    'entity_type' => $event->entity_type,
                    'event_type' => $event->event_type,
                    'matches' => $matchCount,
                ],
            ],
            'confirm_token' => (string) Str::uuid(),
        ]);

        $this->bridge->emitCorrectionSuggestion($event->rewindCase, $event, $fix);

        return $fix;
    }
}
