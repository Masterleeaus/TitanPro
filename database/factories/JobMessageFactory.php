<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Job;
use App\Models\JobMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobMessage>
 */
class JobMessageFactory extends Factory
{
    protected $model = JobMessage::class;

    public function definition(): array
    {
        return [
            'job_id'      => Job::factory(),
            'customer_id' => Customer::factory(),
            'channel'     => 'email',
            'event'       => 'job_scheduled',
            'recipient'   => fake()->safeEmail(),
            'body'        => 'Your job has been scheduled.',
            'status'      => 'sent',
            'error'       => null,
        ];
    }

    public function forJob(Job $job): static
    {
        return $this->state([
            'job_id'      => $job->id,
            'customer_id' => $job->customer_id,
        ]);
    }
}
