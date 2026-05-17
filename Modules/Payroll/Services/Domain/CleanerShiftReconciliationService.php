<?php

namespace Modules\Payroll\Services\Domain;

class CleanerShiftReconciliationService
{
    public function compare(array $rosteredShifts, array $approvedTimeEntries): array
    {
        $issues = [];
        $approvedByShift = [];

        foreach ($approvedTimeEntries as $entry) {
            if (! empty($entry['shift_id'])) {
                $approvedByShift[$entry['shift_id']] = $entry;
            }
        }

        foreach ($rosteredShifts as $shift) {
            $shiftId = $shift['id'] ?? null;
            if (! $shiftId || ! isset($approvedByShift[$shiftId])) {
                $issues[] = [
                    'shift_id' => $shiftId,
                    'type' => 'missing_time_entry',
                    'message' => 'Rostered cleaner shift has no approved time entry.',
                ];
                continue;
            }

            $expected = (float) ($shift['hours'] ?? 0);
            $actual = (float) ($approvedByShift[$shiftId]['hours'] ?? 0);
            if (abs($expected - $actual) >= 0.25) {
                $issues[] = [
                    'shift_id' => $shiftId,
                    'type' => 'hours_variance',
                    'expected_hours' => $expected,
                    'actual_hours' => $actual,
                ];
            }
        }

        return [
            'passed' => count($issues) === 0,
            'issues' => $issues,
        ];
    }
}
