<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Estimate;
use App\Models\EstimatePackage;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EstimatePackage>
 */
class EstimatePackageFactory extends Factory
{
    protected $model = EstimatePackage::class;

    public function definition(): array
    {
        return [
            'estimate_id'    => Estimate::factory(),
            'tier'           => 'good',
            'label'          => 'Basic',
            'description'    => null,
            'subtotal'       => 0,
            'tax_amount'     => 0,
            'total'          => 0,
            'is_recommended' => false,
        ];
    }

    public function forEstimate(Estimate $estimate, string $tier = 'good'): static
    {
        return $this->state([
            'estimate_id' => $estimate->id,
            'tier'        => $tier,
        ]);
    }
}
