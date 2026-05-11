<?php

namespace Modules\JobManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\JobManager\Entities\ServicePart;


namespace ModulesJobManagerDatabaseFactories;


namespace Modules\JobManager\Database\Factories;

use Modules\JobManager\Entities\ServicePart;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServicePartFactory extends Factory
{
    protected $model = ServicePart::class;

    public function definition(): array
    {
        return [
            'sku' => 'PRT-'.strtoupper($this->faker->bothify('???###')),
            'name' => $this->faker->words(2, true),
            'sale_price' => $this->faker->randomFloat(2, 10, 250),
        ];
    }
}
