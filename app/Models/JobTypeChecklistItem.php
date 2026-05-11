<?php

namespace App\Models;

use App\Contracts\TenantAware;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobTypeChecklistItem extends Model implements TenantAware
{
    use BelongsToTenant, HasFactory;

    protected $fillable = ['organization_id','job_type_id','task_library_item_id','label','instructions','sort_order','is_required','required_override','requires_photo','condition_type','condition_value'];

    protected function casts(): array
    {
        return ['is_required' => 'boolean','required_override' => 'boolean','requires_photo' => 'boolean','sort_order' => 'integer'];
    }

    protected static function booted(): void
    {
        static::creating(function (self $item): void {
            if ($item->organization_id !== null) {
                return;
            }

            $item->organization_id = auth()->user()?->organization_id
                ?? JobType::withoutGlobalScopes()->whereKey($item->job_type_id)->value('organization_id');
        });
    }

    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function jobType(): BelongsTo { return $this->belongsTo(JobType::class); }
    public function taskLibraryItem(): BelongsTo { return $this->belongsTo(JobChecklistItem::class, 'task_library_item_id'); }
}
