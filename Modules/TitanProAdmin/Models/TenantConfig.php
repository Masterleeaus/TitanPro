<?php

namespace Modules\TitanProAdmin\Models;

use Illuminate\Database\Eloquent\Model;

class TenantConfig extends Model
{
    protected $table = 'titan_admin_tenant_configs';

    protected $fillable = [
        'target_company_id',
        'config',
        'updated_by',
    ];

    protected $casts = [
        'config' => 'array',
    ];
}
