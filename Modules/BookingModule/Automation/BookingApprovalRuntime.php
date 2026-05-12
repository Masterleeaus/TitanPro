<?php

namespace Modules\BookingModule\Automation;

use Modules\BookingModule\Models\CleaningBooking;

class BookingApprovalRuntime
{
    public function requiresApproval(CleaningBooking $booking, array $context = []): bool
    {
        $value = $this->bookingValue($booking, $context);
        $threshold = $this->thresholdForCompany((int) ($booking->company_id ?? 0) ?: null);

        if ($value === null) {
            return false;
        }

        return $value >= $threshold;
    }

    public function initialStatus(CleaningBooking $booking, array $context = []): string
    {
        return $this->requiresApproval($booking, $context) ? 'pending_approval' : 'draft';
    }

    public function timeoutHours(?int $companyId = null): int
    {
        $fallback = (int) config('bookingmodule.automation.approval.timeout_hours', 24);
        $company = $companyId ? config("bookingmodule.automation.approval.timeout_hours_per_company.{$companyId}") : null;

        return max(1, (int) ($company ?? $fallback));
    }

    private function bookingValue(CleaningBooking $booking, array $context = []): ?float
    {
        if (array_key_exists('booking_value', $context) && $context['booking_value'] !== null) {
            return (float) $context['booking_value'];
        }

        if ($booking->booking_value !== null) {
            return (float) $booking->booking_value;
        }

        return null;
    }

    private function thresholdForCompany(?int $companyId = null): float
    {
        $fallback = (float) config('bookingmodule.automation.approval.high_value_threshold', 1000);
        $company = $companyId ? config("bookingmodule.automation.approval.high_value_threshold_per_company.{$companyId}") : null;

        return (float) ($company ?? $fallback);
    }
}
