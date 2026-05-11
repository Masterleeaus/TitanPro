<?php

namespace Database\Factories;

use App\Models\MessageTemplate;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MessageTemplate>
 */
class MessageTemplateFactory extends Factory
{
    protected $model = MessageTemplate::class;

    /** Track used event/channel combos per org to satisfy the unique constraint. */
    private static array $used = [];

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'event'           => fake()->randomElement(array_keys(MessageTemplate::events())),
            'channel'         => fake()->randomElement(['email', 'sms']),
            'subject'         => fake()->sentence(5),
            'body'            => 'Hello {{customer_name}}, your job is scheduled.',
            'is_active'       => true,
        ];
    }

    public function forOrg(int $organizationId, string $event = 'job_scheduled', string $channel = 'email'): static
    {
        return $this->state([
            'organization_id' => $organizationId,
            'event'           => $event,
            'channel'         => $channel,
        ]);
    }
}
