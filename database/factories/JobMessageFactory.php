<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Job;
use App\Models\JobMessage;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobMessage>
 */
class JobMessageFactory extends Factory
{
    protected $model = JobMessage::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'job_id'          => Job::factory(),
            'customer_id'     => Customer::factory(),
            'channel'         => fake()->randomElement(['email', 'sms']),
            'event'           => fake()->randomElement(['job_scheduled', 'job_reminder', 'en_route', 'job_completed']),
            'recipient'       => fake()->safeEmail(),
            'body'            => fake()->sentence(),
            'status'          => fake()->randomElement(['sent', 'failed', 'delivered', 'pending']),
            'error'           => null,
        ];
    }

    public function sent(): static
    {
        return $this->state(['status' => 'sent']);
    }

    public function failed(): static
    {
        return $this->state([
            'status' => 'failed',
            'error'  => fake()->sentence(),
        ]);
    }
}
