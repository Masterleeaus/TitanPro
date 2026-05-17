<?php

namespace Modules\TitanProAdmin\Models;

use App\Models\Organization;

class ModuleToggle extends Organization
{
    protected $table = 'organizations';

    protected $casts = [
        'enabled_modules' => 'array',
        'suspended_at' => 'datetime',
    ];
}
