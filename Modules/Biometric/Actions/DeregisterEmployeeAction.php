<?php

namespace Modules\Biometric\Actions;

use Modules\Biometric\Events\EmployeeDeregisteredFromDevice;

class DeregisterEmployeeAction
{
    /**
     * @param  array{company_id:int,employee_id:string|int,device_serial_number?:string|null}  $payload
     * @return array{status:string,company_id:int,employee_id:string,device_serial_number:?string}
     */
    public function execute(array $payload): array
    {
        $result = [
            'status' => 'deregistered',
            'company_id' => (int) ($payload['company_id'] ?? 0),
            'employee_id' => (string) ($payload['employee_id'] ?? ''),
            'device_serial_number' => isset($payload['device_serial_number']) ? (string) $payload['device_serial_number'] : null,
        ];

        event(new EmployeeDeregisteredFromDevice(
            companyId: $result['company_id'],
            employeeId: $result['employee_id'],
            deviceSerial: $result['device_serial_number'],
        ));

        return $result;
    }
}

