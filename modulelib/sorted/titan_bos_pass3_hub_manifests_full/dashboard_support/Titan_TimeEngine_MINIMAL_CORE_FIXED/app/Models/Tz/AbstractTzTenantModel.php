<?php

namespace App\Models\Tz;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractTzTenantModel extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $guarded = ['id'];

    protected array $tenantColumns = [
        'id',
        'team_id',
        'company_id',
        'user_id',
        'created_by_team_id',
    ];

    protected function casts(): array
    {
        return [];
    }
}
