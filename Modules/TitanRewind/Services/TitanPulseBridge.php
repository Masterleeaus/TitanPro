<?php

namespace Modules\TitanRewind\Services;

use Illuminate\Support\Facades\Log;
use Modules\TitanRewind\Models\RewindCase;
use Modules\TitanRewind\Models\RewindEvent;
use Modules\TitanRewind\Models\RewindFix;
use Modules\TitanZero\Services\ZeroGateway;

class TitanPulseBridge
{
    public function emitCorrectionSuggestion(RewindCase $case, RewindEvent $event, RewindFix $fix): array
    {
        $payload = [
            'type' => 'titan_rewind.corrective_suggestion',
            'payload' => [
                'case_id' => $case->id,
                'event_id' => $event->id,
                'fix_id' => $fix->id,
                'entity_type' => $event->entity_type,
                'event_type' => $event->event_type,
            ],
        ];

        try {
            if (class_exists(ZeroGateway::class)) {
                return app(ZeroGateway::class)->ingestSignal($payload, $case->company_id);
            }
        } catch (\Throwable $exception) {
            Log::warning('TitanRewind failed to emit corrective suggestion signal.', [
                'case_id' => $case->id,
                'event_id' => $event->id,
                'error' => $exception->getMessage(),
            ]);
        }

        Log::info('TitanRewind corrective suggestion signal queued locally.', $payload['payload']);

        return ['status' => 'queued', 'signal' => $payload];
    }
}
