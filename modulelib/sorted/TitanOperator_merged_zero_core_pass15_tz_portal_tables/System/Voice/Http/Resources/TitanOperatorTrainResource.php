<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Voice\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TitanOperatorTrainResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'file'       => $this->file,
            'url'        => $this->url,
            'text'       => $this->url,
            'name'       => $this->name,
            'status'     => $this->trained_at ? 'Trained' : 'Not Trained',
        ];
    }
}
