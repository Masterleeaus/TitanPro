<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Voice\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TitanOperatorConversationHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
