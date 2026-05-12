<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\CleaningJobs\Traits\BelongsToTenant;

class WOType extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'wo_types';

    protected $fillable = [
        'type',
        'name',
        'description',
        'parent_id',
        'company_id',
    ];

    protected function casts(): array
    {
        return [
            'company_id' => 'integer',
            'parent_id' => 'integer',
        ];
    }
}
