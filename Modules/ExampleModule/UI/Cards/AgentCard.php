<?php

namespace Modules\ExampleModule\UI\Cards;


final class AgentCard
{
    public function __construct(public string $title = '', public mixed $value = null, public array $meta = []) {}

    public function toArray(): array
    {
        return ['title' => $this->title, 'value' => $this->value, 'meta' => $this->meta];
    }

}
