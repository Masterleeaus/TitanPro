<?php

namespace Modules\BookingModule\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentCallbackFactory extends Factory
{
    protected $model = \Modules\BookingModule\Entities\AppointmentCallback::class;

    public function definition(): array
    {
        return [];
    }
}
