<?php

namespace Modules\BookingModule\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = \Modules\BookingModule\Entities\Appointment::class;

    public function definition(): array
    {
        return [];
    }
}
