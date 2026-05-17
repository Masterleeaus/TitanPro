<?php

namespace Modules\CRMCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\CRMCore\Traits\UsesScopedByCompany;

class DealPipeline extends Model
{
    use HasFactory, UsesScopedByCompany;

    protected $table = 'deal_pipelines';

    protected $fillable = [
        'name',
        'description',
        'is_default',
        'is_active',
        'position',
        'company_id',
        'tenant_id',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'position' => 'integer',
        'company_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the stages associated with this pipeline, ordered by position.
     */
    public function stages()
    {
        return $this->hasMany(DealStage::class, 'pipeline_id')->orderBy('position');
    }

    /**
     * Get all deals that belong to this pipeline.
     */
    public function deals()
    {
        return $this->hasMany(Deal::class, 'pipeline_id');
    }

    // createdBy() and updatedBy() relationships handled by UserActionsTrait
}
