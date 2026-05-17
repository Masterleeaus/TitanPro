<?php

declare(strict_types=1);

namespace Modules\Dispatch\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Dispatch\Models\DispatchWorkOrder;

class DispatchWorkOrderFactory extends Factory
{
    protected $model = DispatchWorkOrder::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'reference' => 'DWO-'.fake()->unique()->numerify('######'),
            'status' => 'draft',
            'priority' => fake()->randomElement(['low', 'normal', 'high', 'urgent']),
            'location' => fake()->streetAddress(),
            'estimated_hours' => fake()->randomFloat(2, 1, 8),
            'scheduled_for' => fake()->optional()->dateTimeBetween('now', '+14 days'),
            'metadata' => [],
        ];
    }
}
