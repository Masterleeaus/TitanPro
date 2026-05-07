<?php

namespace Database\Factories;

use App\Models\WorkflowAuditLog;
use App\Models\WorkflowInstance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkflowAuditLog>
 */
class WorkflowAuditLogFactory extends Factory
{
    protected $model = WorkflowAuditLog::class;

    public function definition(): array
    {
        return [
            'workflow_instance_id' => WorkflowInstance::factory(),
            'company_id'           => null,
            'step_key'             => 'step_one',
            'step_type'            => 'action',
            'outcome'              => 'completed',
            'actor'                => 'system',
            'payload'              => null,
            'error'                => null,
            'duration_ms'          => $this->faker->numberBetween(1, 500),
        ];
    }
}
