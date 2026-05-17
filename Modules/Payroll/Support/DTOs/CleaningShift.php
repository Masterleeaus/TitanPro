<?php

namespace Modules\Payroll\Support\DTOs;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

class CleaningShift
{
    public function __construct(
        public readonly string $siteCode,
        public readonly CarbonInterface $startedAt,
        public readonly CarbonInterface $endedAt,
        public readonly float $hourlyRate,
        public readonly bool $isApproved = true,
        public readonly bool $isPublicHoliday = false,
        public readonly bool $requiresTravelAllowance = false,
        public readonly bool $requiresSiteAllowance = false,
        public readonly bool $requiresEquipmentAllowance = false,
        public readonly ?float $breakMinutes = null,
        public readonly array $metadata = [],
    ) {}

    public static function fromArray(array $shift): self
    {
        return new self(
            siteCode: (string) ($shift['site_code'] ?? $shift['siteCode'] ?? 'unknown'),
            startedAt: CarbonImmutable::parse($shift['started_at'] ?? $shift['startedAt']),
            endedAt: CarbonImmutable::parse($shift['ended_at'] ?? $shift['endedAt']),
            hourlyRate: (float) ($shift['hourly_rate'] ?? $shift['hourlyRate'] ?? 0),
            isApproved: (bool) ($shift['is_approved'] ?? $shift['isApproved'] ?? true),
            isPublicHoliday: (bool) ($shift['is_public_holiday'] ?? $shift['isPublicHoliday'] ?? false),
            requiresTravelAllowance: (bool) ($shift['requires_travel_allowance'] ?? $shift['requiresTravelAllowance'] ?? false),
            requiresSiteAllowance: (bool) ($shift['requires_site_allowance'] ?? $shift['requiresSiteAllowance'] ?? false),
            requiresEquipmentAllowance: (bool) ($shift['requires_equipment_allowance'] ?? $shift['requiresEquipmentAllowance'] ?? false),
            breakMinutes: isset($shift['break_minutes']) ? (float) $shift['break_minutes'] : (isset($shift['breakMinutes']) ? (float) $shift['breakMinutes'] : null),
            metadata: (array) ($shift['metadata'] ?? []),
        );
    }

    public function paidHours(): float
    {
        $minutes = max(0, $this->startedAt->diffInMinutes($this->endedAt));
        $break = max(0, (float) ($this->breakMinutes ?? 0));
        return round(max(0, $minutes - $break) / 60, 4);
    }
}
