<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\JobChecklistItem;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobChecklistItem>
 */
class JobChecklistItemFactory extends Factory
{
    protected $model = JobChecklistItem::class;

    public function definition(): array
    {
        return [
            'organization_id'            => null,
            'job_id'                     => Job::factory(),
            'job_type_checklist_item_id' => null,
            'label'                      => fake()->sentence(3),
            'category'                   => 'general',
            'instructions'               => null,
            'estimated_minutes'          => null,
            'sort_order'                 => 0,
            'is_required'                => false,
            'requires_photo'             => false,
            'completed_at'               => null,
        ];
    }

    public function forJob(Job $job): static
    {
        return $this->state([
            'organization_id' => $job->organization_id,
            'job_id'          => $job->id,
        ]);
    }
}
