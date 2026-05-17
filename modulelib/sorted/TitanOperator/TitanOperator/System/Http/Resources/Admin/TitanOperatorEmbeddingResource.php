<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TitanOperatorEmbeddingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'file'       => $this->file,
            'url'        => $this->url,
            'title'      => $this->title,
            'content'    => $this->content,
            'status'     => $this->trained_at ? 'Trained' : 'Not Trained',
        ];
    }
}
