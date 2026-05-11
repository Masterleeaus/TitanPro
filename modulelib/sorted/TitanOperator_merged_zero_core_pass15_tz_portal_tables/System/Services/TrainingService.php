<?php

namespace App\Extensions\TitanOperator\System\Services;

use App\Extensions\TitanOperator\System\Models\TitanOperator;

class TrainingService
{
    public TitanOperator $titan_operator;

    public function setTitanOperator(TitanOperator $titan_operator): static
    {
        $this->titan_operator = $titan_operator;

        return $this;
    }
}
