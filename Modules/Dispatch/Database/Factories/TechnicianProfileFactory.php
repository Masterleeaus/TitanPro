<?php

declare(strict_types=1);

namespace Modules\Dispatch\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Dispatch\Models\TechnicianProfile;

class TechnicianProfileFactory extends Factory
{
    protected $model = TechnicianProfile::class;

    public function definition(): array
    {
        return [
            'display_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'capacity_minutes_per_day' => 480,
            'active' => true,
        ];
    }
}
