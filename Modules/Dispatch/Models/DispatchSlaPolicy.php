<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class DispatchSlaPolicy extends Model
{
    use BelongsToTenant;

    protected $table = 'dispatch_sla_policies';

    protected $fillable = ['company_id', 'name', 'priority', 'response_minutes', 'completion_minutes', 'active', 'metadata'];

    protected function casts(): array
    {
        return ['active' => 'boolean', 'metadata' => 'array'];
    }
}
