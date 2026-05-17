<?php

namespace Modules\Security\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SecurityModuleStatusResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'module' => $this->resource['module'] ?? 'security',
            'status' => $this->resource['status'] ?? 'unknown',
            'counts' => $this->resource['counts'] ?? [],
            'pending_approvals' => $this->resource['pending_approvals'] ?? 0,
            'pending_validations' => $this->resource['pending_validations'] ?? 0,
        ];
    }
}
