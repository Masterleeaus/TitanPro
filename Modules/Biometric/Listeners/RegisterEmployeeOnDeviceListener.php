<?php

namespace Modules\Biometric\Listeners;

use Modules\Biometric\Actions\RegisterEmployeeOnDeviceAction;

class RegisterEmployeeOnDeviceListener
{
    public function __construct(private readonly RegisterEmployeeOnDeviceAction $action) {}

    /** @param  array<string, mixed>|object  $payload */
    public function handle(array|object $payload): void
    {
        $data = is_array($payload) ? $payload : get_object_vars($payload);

        $this->action->execute([
            'company_id' => (int) ($data['company_id'] ?? 0),
            'employee_id' => (string) ($data['employee_id'] ?? ''),
            'device_serial_number' => isset($data['device_serial_number']) ? (string) $data['device_serial_number'] : null,
        ]);
    }
}

