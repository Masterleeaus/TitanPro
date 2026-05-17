<?php

namespace App\Extensions\TitanOperator\System\Services\OpenAI;

use App\Extensions\TitanOperator\System\Models\TitanOperator;

class MessageService
{
    /**
     * TitanOperator instance
     */
    public TitanOperator $titan_operator;

    public function generateMessage() {}

    public function getTitanOperator(): TitanOperator
    {
        return $this->titan_operator;
    }

    public function setTitanOperator(TitanOperator $titan_operator): self
    {
        $this->titan_operator = $titan_operator;

        return $this;
    }
}
