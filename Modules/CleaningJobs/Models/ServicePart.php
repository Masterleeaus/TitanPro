<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\CleaningJobs\Traits\BelongsToTenant;

class ServicePart extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'service_parts';

    protected $fillable = [
        'title',
        'sku',
        'unit',
        'price',
        'description',
        'type',
        'parent_id',
        'company_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'company_id' => 'integer',
            'parent_id' => 'integer',
        ];
    }

    public function serviceTasks()
    {
        return $this->hasMany(ServiceTask::class, 'service_id');
    }
}
