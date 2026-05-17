<?php

namespace Modules\ZeroPayModule\Database\Factories;

class ZeroPaySessionFactory
{
    public function definition(): array
    {
        return ['company_id' => 1, 'user_id' => 1, 'name' => 'Example', 'status' => 'draft', 'meta' => []];
    }
}
