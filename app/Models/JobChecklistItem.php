<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id','job_id','job_type_checklist_item_id','label','category','instructions','estimated_minutes','sort_order','is_required','requires_photo','completed_at'];

    protected function casts(): array
    {
        return ['is_required' => 'boolean','requires_photo' => 'boolean','sort_order' => 'integer','estimated_minutes' => 'integer','completed_at' => 'datetime'];
    }

    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function job(): BelongsTo { return $this->belongsTo(Job::class); }
    public function template(): BelongsTo { return $this->belongsTo(JobTypeChecklistItem::class, 'job_type_checklist_item_id'); }
    public function isCompleted(): bool { return $this->completed_at !== null; }
}
