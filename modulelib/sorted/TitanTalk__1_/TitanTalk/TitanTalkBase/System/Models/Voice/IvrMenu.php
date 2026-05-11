<?php

namespace App\Extensions\MarketingBot\System\Models\Voice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IvrMenu extends Model
{
    protected $table = 'titantalk_ivr_menus';

    protected $fillable = [
        'company_id',
        'name',
        'greeting_text',
        'repeat_count',
        'timeout_seconds',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'repeat_count' => 'integer',
        'timeout_seconds' => 'integer',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(IvrOption::class, 'ivr_menu_id');
    }
}
