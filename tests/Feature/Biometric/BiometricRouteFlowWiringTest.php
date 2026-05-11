<?php

use Modules\Biometric\Http\Controllers\BiometricDeviceController;
use Modules\Biometric\Http\Controllers\BiometricEmployeeController;
use Modules\Biometric\Http\Controllers\ZKTecoController;

test('biometric web flow controller actions referenced by routes exist', function () {
    expect(method_exists(BiometricDeviceController::class, 'changeStatus'))->toBeTrue()
        ->and(method_exists(BiometricDeviceController::class, 'syncEmployees'))->toBeTrue()
        ->and(method_exists(BiometricEmployeeController::class, 'fetchAll'))->toBeTrue()
        ->and(method_exists(BiometricEmployeeController::class, 'getEmployeeInfo'))->toBeTrue();
});

test('biometric device api flow actions referenced by routes exist', function () {
    expect(method_exists(ZKTecoController::class, 'handshake'))->toBeTrue()
        ->and(method_exists(ZKTecoController::class, 'handleAttendanceData'))->toBeTrue()
        ->and(method_exists(ZKTecoController::class, 'handleGetRequest'))->toBeTrue()
        ->and(method_exists(ZKTecoController::class, 'handleDeviceCommand'))->toBeTrue()
        ->and(method_exists(ZKTecoController::class, 'handlePing'))->toBeTrue()
        ->and(method_exists(ZKTecoController::class, 'test'))->toBeTrue();
});

