<?php

namespace Modules\BookingModule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\BookingModule\Services\BookingLifecycleLogService;

class RecordBookingLifecycleLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    public function __construct(
        public readonly string $subjectType,
        public readonly int $subjectId,
        public readonly string $event,
        public readonly ?int $companyId = null,
        public readonly ?string $fromStatus = null,
        public readonly ?string $toStatus = null,
        public readonly array $payload = [],
        public readonly ?int $actorId = null,
    ) {}

    public static function fromModel(
        Model $subject,
        string $event,
        ?string $fromStatus = null,
        ?string $toStatus = null,
        array $payload = [],
        ?int $actorId = null,
    ): self {
        return new self(
            $subject::class,
            (int) $subject->getKey(),
            $event,
            (int) ($subject->company_id ?? 0) ?: null,
            $fromStatus,
            $toStatus,
            $payload,
            $actorId,
        );
    }

    public function handle(BookingLifecycleLogService $logger): void
    {
        $logger->record(
            $this->subjectType,
            $this->subjectId,
            $this->event,
            $this->companyId,
            $this->fromStatus,
            $this->toStatus,
            $this->payload,
            $this->actorId,
        );
    }
}
