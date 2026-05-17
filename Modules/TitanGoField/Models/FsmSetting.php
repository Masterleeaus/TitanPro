<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;

class FsmSetting extends Model
{
    protected $table = 'fsm_settings';

    protected $fillable = [
        'company_id', 'vertical', 'features', 'terminology', 'branding', 'webhook_url',
    ];

    protected $casts = [
        'features'    => 'array',
        'terminology' => 'array',
        'branding'    => 'array',
    ];

    public static function forCompany(int $companyId): self
    {
        return static::firstOrNew(['company_id' => $companyId], [
            'vertical' => config('titango_field.default_vertical', 'general_trades'),
        ]);
    }
}
