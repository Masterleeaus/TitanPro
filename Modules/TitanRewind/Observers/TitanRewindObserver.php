<?php

namespace Modules\TitanRewind\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\TitanRewind\Services\RewindAuditService;

class TitanRewindObserver
{
    public function __construct(private readonly RewindAuditService $audit) {}

    public function record(string $eventName, Model $model): void
    {
        if (! $this->shouldTrack($model)) {
            return;
        }

        $eventType = $this->eventType($eventName);
        $payload = $this->payloadFor($eventType, $model);

        if ($eventType === 'updated' && empty($payload['changes'])) {
            return;
        }

        $companyId = $this->resolveCompanyId($model);
        if ($companyId === null) {
            return;
        }

        $actor = Auth::user();

        $this->audit->appendEvent([
            'company_id' => $companyId,
            'case_key' => sprintf('%s:%s', $model::class, $model->getKey()),
            'title' => sprintf('%s #%s mutation detected', class_basename($model), (string) $model->getKey()),
            'severity' => 'medium',
            'source_type' => 'model_observer',
            'source_id' => $eventType,
            'event_type' => $eventType,
            'entity_type' => $model::class,
            'entity_id' => (string) $model->getKey(),
            'actor_type' => $actor ? 'user' : 'system',
            'actor_id' => $actor?->getAuthIdentifier(),
            'payload_json' => array_merge($payload, [
                'model' => $model::class,
                'model_id' => $model->getKey(),
            ]),
            'created_at' => now(),
        ]);
    }

    private function shouldTrack(Model $model): bool
    {
        $ignored = config('titan-rewind.ignored_models', []);
        foreach ($ignored as $ignoredClass) {
            if ($model instanceof $ignoredClass) {
                return false;
            }
        }

        $tracked = config('titan-rewind.tracked_models', []);
        foreach ($tracked as $trackedClass) {
            if ($model instanceof $trackedClass) {
                return true;
            }
        }

        return false;
    }

    private function eventType(string $eventName): string
    {
        return match (true) {
            str_contains($eventName, 'eloquent.created:') => 'created',
            str_contains($eventName, 'eloquent.deleted:') => 'deleted',
            default => 'updated',
        };
    }

    private function payloadFor(string $eventType, Model $model): array
    {
        return match ($eventType) {
            'created' => [
                'changes' => Arr::except($model->getAttributes(), ['created_at', 'updated_at']),
            ],
            'deleted' => [
                'before' => Arr::except($model->getOriginal(), ['created_at', 'updated_at']),
            ],
            default => [
                'changes' => Arr::except($model->getChanges(), ['updated_at']),
                'before' => Arr::only($model->getOriginal(), array_keys(Arr::except($model->getChanges(), ['updated_at']))),
            ],
        };
    }

    private function resolveCompanyId(Model $model): ?int
    {
        $companyId = $model->getAttribute('company_id') ?? $model->getAttribute('organization_id');

        return is_numeric($companyId) ? (int) $companyId : null;
    }
}
