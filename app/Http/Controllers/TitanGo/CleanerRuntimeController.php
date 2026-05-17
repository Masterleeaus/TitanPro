<?php

namespace App\Http\Controllers\TitanGo;

use App\Http\Controllers\Controller;
use App\Services\TitanGo\CleanerLiveStatusService;
use App\Services\TitanGo\OfflineActionQueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Modules\TitanGoField\Models\FieldJob;

class CleanerRuntimeController extends Controller
{
    public function storeLocation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'heading' => ['nullable', 'numeric'],
            'speed' => ['nullable', 'numeric'],
            'accuracy' => ['nullable', 'numeric'],
            'recorded_at' => ['nullable', 'date'],
        ]);

        $user = $request->user();
        $payload = [
            'user_id' => $user?->id,
            'company_id' => $user?->company_id ?? $user?->organization_id,
            'latitude' => (float) $data['latitude'],
            'longitude' => (float) $data['longitude'],
            'heading' => $data['heading'] ?? null,
            'speed' => $data['speed'] ?? null,
            'accuracy' => $data['accuracy'] ?? null,
            'recorded_at' => $data['recorded_at'] ?? now()->toIso8601String(),
            'received_at' => now()->toIso8601String(),
            'source' => 'titango-cleaner-pwa',
        ];

        Cache::put('titango:cleaner-location:'.($user?->id ?? 'guest'), $payload, now()->addHours(12));
        app(CleanerLiveStatusService::class)->update($user?->id, 'available', null, ['last_location' => $payload]);

        return response()->json(['ok' => true, 'location' => $payload]);
    }

    public function missionPack(Request $request): JsonResponse
    {
        $user = $request->user();
        $companyId = $user?->company_id ?? $user?->organization_id;
        $jobs = collect();

        if ($user && $companyId && class_exists(FieldJob::class)) {
            $jobs = FieldJob::query()
                ->where('company_id', $companyId)
                ->where(function ($query) use ($user) {
                    $query->whereNull('technician_id')->orWhere('technician_id', $user->id);
                })
                ->whereNotIn('status', [FieldJob::STATUS_COMPLETED, FieldJob::STATUS_CANCELLED])
                ->orderByRaw('scheduled_start IS NULL')
                ->orderBy('scheduled_start')
                ->limit(25)
                ->get(['id', 'reference', 'status', 'priority', 'description', 'notes', 'scheduled_start', 'scheduled_end', 'meta']);
        }

        return response()->json([
            'ok' => true,
            'generated_at' => now()->toIso8601String(),
            'expires_at' => now()->addHours(14)->toIso8601String(),
            'jobs' => $jobs->map(fn (FieldJob $job): array => [
                'id' => $job->id,
                'reference' => $job->reference,
                'status' => $job->status,
                'priority' => $job->priority,
                'description' => $job->description,
                'notes' => $job->notes,
                'scheduled_start' => $job->scheduled_start?->toIso8601String(),
                'scheduled_end' => $job->scheduled_end?->toIso8601String(),
                'required_proof' => ['before_photo', 'after_photo', 'issue_photo_optional', 'signature_optional'],
            ])->values(),
            'checklists' => [],
            'offline_actions' => ['location_ping', 'check_in', 'start', 'pause', 'resume', 'before_photo', 'after_photo', 'issue_photo', 'unsafe_site_report', 'request_help', 'complete'],
            'ui' => ['mode' => 'cleaner-field-runtime', 'admin_features' => false],
        ]);
    }

    public function liveStatus(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'ok' => true,
            'status' => app(CleanerLiveStatusService::class)->get($user?->id),
            'queued_actions' => count(app(OfflineActionQueueService::class)->all($user?->id)),
            'server_time' => now()->toIso8601String(),
        ]);
    }

    public function syncQueue(Request $request): JsonResponse
    {
        $data = $request->validate([
            'actions' => ['nullable', 'array'],
            'actions.*.type' => ['nullable', 'string', 'max:80'],
            'actions.*.job_id' => ['nullable'],
            'actions.*.payload' => ['nullable', 'array'],
            'actions.*.created_at' => ['nullable', 'date'],
        ]);

        return response()->json(app(OfflineActionQueueService::class)->accept($request->user()?->id, $data['actions'] ?? []));
    }

    public function updateJobRuntime(Request $request, FieldJob $job): JsonResponse
    {
        $data = $request->validate(['action' => ['required', 'string', 'in:check_in,start,pause,resume,complete']]);
        $user = $request->user();

        match ($data['action']) {
            'check_in', 'start', 'resume' => $job->forceFill([
                'status' => FieldJob::STATUS_IN_PROGRESS,
                'started_at' => $job->started_at ?: now(),
                'technician_id' => $job->technician_id ?: $user?->id,
            ])->save(),
            'pause' => $job->forceFill(['status' => FieldJob::STATUS_ON_HOLD])->save(),
            'complete' => $job->forceFill(['status' => FieldJob::STATUS_COMPLETED, 'completed_at' => now()])->save(),
        };

        app(CleanerLiveStatusService::class)->update($user?->id, $data['action'], $job);

        return response()->json(['ok' => true, 'job' => [
            'id' => $job->id,
            'reference' => $job->reference,
            'status' => $job->status,
            'started_at' => $job->started_at?->toIso8601String(),
            'completed_at' => $job->completed_at?->toIso8601String(),
        ]]);
    }

    public function storeProof(Request $request): JsonResponse
    {
        $data = $request->validate([
            'job_id' => ['nullable', 'integer'],
            'type' => ['required', 'string', 'in:before,after,issue,signature,other'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,heic,heif', 'max:15360'],
            'note' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $user = $request->user();
        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('titango/proof/'.($user?->id ?? 'guest'), 'public');
        }

        $event = [
            'type' => 'proof_'.$data['type'],
            'job_id' => $data['job_id'] ?? null,
            'user_id' => $user?->id,
            'company_id' => $user?->company_id ?? $user?->organization_id,
            'path' => $path,
            'url' => $path ? Storage::disk('public')->url($path) : null,
            'note' => $data['note'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'created_at' => now()->toIso8601String(),
        ];

        $key = 'titango:proof-events:'.($user?->id ?? 'guest');
        $events = Cache::get($key, []);
        array_unshift($events, $event);
        Cache::put($key, array_slice($events, 0, 150), now()->addDays(14));

        app(OfflineActionQueueService::class)->accept($user?->id, [[
            'type' => $event['type'],
            'job_id' => $event['job_id'],
            'payload' => $event,
            'created_at' => $event['created_at'],
        ]]);

        return response()->json(['ok' => true, 'proof' => $event]);
    }

    public function requestHelp(Request $request): JsonResponse
    {
        return $this->storeSafetyEvent($request, 'help_requested');
    }

    public function reportUnsafeSite(Request $request): JsonResponse
    {
        return $this->storeSafetyEvent($request, 'unsafe_site_reported');
    }

    protected function storeSafetyEvent(Request $request, string $type): JsonResponse
    {
        $user = $request->user();
        $event = [
            'type' => $type,
            'user_id' => $user?->id,
            'company_id' => $user?->company_id ?? $user?->organization_id,
            'message' => (string) $request->input('message', ''),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'created_at' => now()->toIso8601String(),
        ];

        $key = 'titango:safety-events:'.($user?->id ?? 'guest');
        $events = Cache::get($key, []);
        array_unshift($events, $event);
        Cache::put($key, array_slice($events, 0, 50), now()->addDays(7));
        app(CleanerLiveStatusService::class)->update($user?->id, $type, null, ['safety_event' => $event]);

        return response()->json(['ok' => true, 'event' => $event]);
    }
}
