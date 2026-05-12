<?php

namespace Modules\Biometric\Actions;

use InvalidArgumentException;
use Illuminate\Http\Request;
use Modules\Biometric\Entities\BiometricAttendance;
use Modules\Biometric\Entities\BiometricDevice;
use Modules\Biometric\Entities\BiometricEmployee;
use Modules\Biometric\Events\AttendanceRecorded;

class RecordAttendanceAction
{
    public function execute(array $payload): BiometricAttendance
    {
        $companyId = (int) ($payload['company_id'] ?? 0);
        if ($companyId <= 0) {
            throw new InvalidArgumentException('company_id is required.');
        }

        $attendance = BiometricAttendance::query()->create([
            'company_id' => $companyId,
            'user_id' => isset($payload['user_id']) ? (int) $payload['user_id'] : null,
            'device_name' => (string) ($payload['device_name'] ?? 'Unknown device'),
            'device_serial_number' => (string) ($payload['device_serial_number'] ?? 'N/A'),
            'table' => (string) ($payload['table'] ?? 'iclock'),
            'stamp' => (string) ($payload['stamp'] ?? ''),
            'employee_id' => (string) ($payload['employee_id'] ?? ''),
            'timestamp' => (string) ($payload['timestamp'] ?? now()->toDateTimeString()),
            'status1' => isset($payload['status1']) ? (int) $payload['status1'] : 0,
            'status2' => isset($payload['status2']) ? (int) $payload['status2'] : null,
            'status3' => isset($payload['status3']) ? (int) $payload['status3'] : null,
            'status4' => isset($payload['status4']) ? (int) $payload['status4'] : null,
            'status5' => isset($payload['status5']) ? (int) $payload['status5'] : null,
        ]);

        event(new AttendanceRecorded(
            companyId: (int) $attendance->company_id,
            employeeId: (string) $attendance->employee_id,
            userId: $attendance->user_id ? (int) $attendance->user_id : null,
            occurredAt: $attendance->timestamp,
            clockIn: (int) ($attendance->status1 ?? 0) === 0,
            workedHours: (float) ($payload['worked_hours'] ?? 0.0),
            deviceSerial: $attendance->device_serial_number,
        ));

        return $attendance;
    }

    public function ingestDeviceRows(array $rows, BiometricDevice $device, Request $request): void
    {
        BiometricEmployee::markAttendanceToDeviceAndApplication($rows, $device, $request);
    }
}
