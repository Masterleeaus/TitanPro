<?php

namespace Database\Factories;

use App\Models\Estimate;
use App\Models\EstimatePackage;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EstimatePackage>
 */
class EstimatePackageFactory extends Factory
{
    protected $model = EstimatePackage::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100, 2000);

        return [
            'organization_id' => Organization::factory(),
            'estimate_id'     => Estimate::factory(),
            'tier'            => fake()->randomElement(['good', 'better', 'best']),
            'label'           => fake()->randomElement(['Basic', 'Standard', 'Premium']),
            'description'     => fake()->optional()->sentence(),
            'subtotal'        => $subtotal,
            'tax_amount'      => round($subtotal * 0.1, 2),
            'total'           => round($subtotal * 1.1, 2),
            'is_recommended'  => false,
        ];
    }

    public function recommended(): static
    {
        return $this->state(['is_recommended' => true]);
    }

    public function forEstimate(Estimate $estimate, string $tier = 'good'): static
    {
        return $this->state([
            'organization_id' => $estimate->organization_id,
            'estimate_id'     => $estimate->id,
            'tier'            => $tier,
        ]);
    }
}
