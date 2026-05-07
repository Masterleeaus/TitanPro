<?php

namespace Database\Factories;

use App\Models\WorkflowInstance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkflowInstance>
 */
class WorkflowInstanceFactory extends Factory
{
    protected $model = WorkflowInstance::class;

    public function definition(): array
    {
        return [
            'company_id'       => null,
            'workflow_id'      => $this->faker->slug(2),
            'workflow_version' => '1.0.0',
            'entity_type'      => null,
            'entity_id'        => null,
            'status'           => 'pending',
            'current_step'     => 'step_one',
            'context'          => [],
            'attempt'          => 0,
            'initiated_by'     => 'system',
            'started_at'       => null,
            'completed_at'     => null,
        ];
    }
}
