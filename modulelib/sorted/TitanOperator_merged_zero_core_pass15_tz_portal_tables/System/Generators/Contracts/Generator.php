<?php

namespace App\Extensions\TitanOperator\System\Generators\Contracts;

use App\Domains\Entity\Enums\EntityEnum;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use App\Extensions\TitanOperator\System\Models\TitanOperatorHistory;
use Illuminate\Database\Eloquent\Collection;

abstract class Generator implements GeneratorInterface
{
    public string $prompt;

    public EntityEnum $entity;

    public TitanOperator $titan_operator;

    public TitanOperatorConversation $conversation;

    public function histories(): Collection|array
    {
        return TitanOperatorHistory::query()
            ->where('conversation_id', $this->conversation->id)
            ->select('message', 'role', 'id')
            ->orderByDesc('id')
            ->limit(10)
            ->get();
    }

    public function setPrompt(string $prompt): static
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function getEntity(): EntityEnum
    {
        return $this->entity;
    }

    public function setEntity(EntityEnum $entity): static
    {
        $this->entity = $entity;

        return $this;
    }

    public function getTitanOperator(): TitanOperator
    {
        return $this->titan_operator;
    }

    public function setTitanOperator(TitanOperator $titan_operator): static
    {
        $this->titan_operator = $titan_operator;

        return $this;
    }

    public function getConversation(): TitanOperatorConversation
    {
        return $this->conversation;
    }

    public function setConversation(TitanOperatorConversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }
}
