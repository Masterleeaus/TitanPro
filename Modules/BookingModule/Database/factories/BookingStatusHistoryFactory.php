<?php

namespace Modules\BookingModule\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookingStatusHistoryFactory extends Factory
{
    protected $model = \Modules\BookingModule\Entities\BookingStatusHistory::class;

    public function definition(): array
    {
        return [];
    }
}
