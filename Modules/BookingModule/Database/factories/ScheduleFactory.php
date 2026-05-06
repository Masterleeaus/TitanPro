<?php

namespace Modules\BookingModule\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = \Modules\BookingModule\Entities\Schedule::class;

    public function definition(): array
    {
        return [];
    }
}
