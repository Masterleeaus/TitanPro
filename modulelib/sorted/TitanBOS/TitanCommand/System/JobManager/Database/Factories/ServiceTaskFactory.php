<?php

namespace App\Extensions\TitanCommand\System\JobManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Extensions\TitanCommand\System\JobManager\Entities\ServiceTask;






class ServiceTaskFactory extends Factory
{
    protected $model = ServiceTask::class;

    public function definition(): array
    {
        return [
            'sku' => 'LAB-'.strtoupper($this->faker->bothify('???###')),
            'name' => $this->faker->words(3, true),
            'default_rate' => $this->faker->randomFloat(2, 80, 180),
        };
    }
}
