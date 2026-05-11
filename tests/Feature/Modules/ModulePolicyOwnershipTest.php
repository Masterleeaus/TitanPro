<?php

use App\Models\Customer;
use App\Models\User;
use Modules\BookingModule\Entities\Appointment;
use Modules\BookingModule\Policies\AppointmentPolicy;
use Modules\CleanQuality\Entities\QcRecord;
use Modules\CleanQuality\Policies\QcRecordPolicy;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Policies\DealPolicy;

test('booking appointment assign denies cross-organization record', function (): void {
    $user = Mockery::mock(User::class)->makePartial();
    $user->organization_id = 1;
    $user->shouldReceive('can')->andReturnTrue();

    $appointment = new Appointment(['company_id' => 2]);

    expect((new AppointmentPolicy)->assign($user, $appointment))->toBeFalse();
});

test('clean quality qc record update denies cross-organization record', function (): void {
    $user = Mockery::mock(User::class)->makePartial();
    $user->organization_id = 10;
    $user->shouldReceive('hasPermissionTo')->andReturnTrue();

    $record = new QcRecord(['company_id' => 11]);

    expect((new QcRecordPolicy)->update($user, $record))->toBeFalse();
});

test('crmcore deal view denies access when related customer belongs to another organization', function (): void {
    $user = Mockery::mock(User::class)->makePartial();
    $user->organization_id = 5;
    $user->shouldReceive('can')->andReturnTrue();

    $deal = new Deal(['crmcore_customer_id' => 99]);
    $deal->setRelation('crmcoreCustomer', new Customer(['organization_id' => 6]));

    expect((new DealPolicy)->view($user, $deal))->toBeFalse();
});

