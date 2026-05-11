<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support;

class ZeroResult
{
    public function __construct(
        public readonly string $status,
        public readonly array $data = [],
        public readonly array $meta = [],
    ) {}

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'data' => $this->data,
            'meta' => $this->meta,
        ];
    }
}
