<?php

namespace Modules\BookingModule\Data;

class BookingSummaryData
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $companyId,
        public readonly ?string $status,
        public readonly ?string $customerName,
        public readonly ?string $scheduledFor,
    ) {}

    public static function fromModel(object $record): self
    {
        return new self(
            $record->id ?? null,
            $record->company_id ?? null,
            $record->booking_status ?? $record->status ?? null,
            $record->customer?->name ?? $record->customer_name ?? null,
            (string) ($record->starts_at ?? $record->date ?? '') ?: null,
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
