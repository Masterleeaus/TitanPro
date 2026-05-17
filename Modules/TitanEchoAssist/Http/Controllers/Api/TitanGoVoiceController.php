<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanEchoAssist\Models\TitanGoVoiceEvent;
use Modules\TitanEchoAssist\Services\TitanGoActionBridge;
use Modules\TitanEchoAssist\Services\TitanGoVoiceActionMapper;
use RuntimeException;

class TitanGoVoiceController extends Controller
{
    public function __construct(
        private readonly TitanGoVoiceActionMapper $mapper,
        private readonly TitanGoActionBridge $bridge
    ) {}

    public function transcribe(Request $request): JsonResponse
    {
        $text = trim((string) $request->input('transcript', ''));

        if ($text === '' && $request->hasFile('audio')) {
            $text = trim((string) $request->input('fallback_transcript', ''));
        }

        if ($text === '') {
            return response()->json([
                'message' => 'Unable to transcribe audio. Provide transcript text or a fallback_transcript.',
            ], 422);
        }

        return response()->json(['transcript' => $text]);
    }

    public function mapAction(Request $request): JsonResponse
    {
        $startedAt = microtime(true);
        $user = $request->user();
        $companyId = (int) ($user?->company_id ?? $user?->organization_id ?? 0);

        $request->validate([
            'transcript' => ['required', 'string', 'max:5000'],
            'job_id' => ['nullable', 'integer'],
            'content' => ['nullable', 'string', 'max:5000'],
        ]);

        $transcript = (string) $request->input('transcript');
        $actionKey = $this->mapper->mapPhraseToAction($transcript);

        $event = TitanGoVoiceEvent::create([
            'company_id' => $companyId,
            'user_id' => $user?->id,
            'job_id' => $request->input('job_id'),
            'action_key' => $actionKey,
            'transcript' => $transcript,
            'matched_phrase' => $this->mapper->matchedPhrase(),
            'status' => $actionKey ? 'dispatched' : 'failed',
            'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
        ]);

        if (! $actionKey) {
            return response()->json([
                'eventId' => $event->id,
                'actionKey' => null,
                'preview' => 'No TitanGo voice action matched.',
                'requiresConfirmation' => false,
                'result' => null,
            ]);
        }

        try {
            $payload = $this->bridge->dispatch($actionKey, [
                'job_id' => (int) $request->input('job_id', 0),
                'company_id' => $companyId,
                'content' => (string) $request->input('content', ''),
                'transcript' => $transcript,
                'preview_only' => true,
            ]);
        } catch (RuntimeException $exception) {
            $event->update(['status' => 'failed', 'result' => ['message' => $exception->getMessage()]]);

            return response()->json(['message' => $exception->getMessage()], 403);
        }

        return response()->json([
            'eventId' => $event->id,
            'actionKey' => $actionKey,
            'preview' => $payload['preview'] ?? null,
            'requiresConfirmation' => (bool) ($payload['requiresConfirmation'] ?? false),
            'result' => $payload['result'] ?? null,
        ]);
    }

    public function confirm(Request $request, int $eventId): JsonResponse
    {
        $startedAt = microtime(true);
        $user = $request->user();
        $companyId = (int) ($user?->company_id ?? $user?->organization_id ?? 0);

        $event = TitanGoVoiceEvent::query()
            ->whereKey($eventId)
            ->where('company_id', $companyId)
            ->where('user_id', $user?->id)
            ->firstOrFail();

        if ($event->action_key === null) {
            $event->update(['status' => 'cancelled']);

            return response()->json(['message' => 'No actionable voice command to confirm.'], 422);
        }

        try {
            $payload = $this->bridge->dispatch($event->action_key, [
                'job_id' => (int) ($event->job_id ?? 0),
                'company_id' => $companyId,
                'content' => (string) $request->input('content', ''),
                'transcript' => (string) $event->transcript,
                'preview_only' => false,
            ]);

            $event->update([
                'status' => 'confirmed',
                'result' => $payload['result'] ?? null,
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
            ]);

            return response()->json([
                'eventId' => $event->id,
                'actionKey' => $event->action_key,
                'preview' => $payload['preview'] ?? null,
                'requiresConfirmation' => (bool) ($payload['requiresConfirmation'] ?? false),
                'result' => $payload['result'] ?? null,
            ]);
        } catch (RuntimeException $exception) {
            $event->update([
                'status' => 'failed',
                'result' => ['message' => $exception->getMessage()],
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
            ]);

            return response()->json(['message' => $exception->getMessage()], 403);
        }
    }
}
