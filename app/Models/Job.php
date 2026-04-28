<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'field_jobs';

    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_EN_ROUTE = 'en_route';
    const STATUS_ARRIVED = 'arrived';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_QUALITY_CHECK = 'quality_check';
    const STATUS_COMPLETED = 'completed';
    const STATUS_INVOICED = 'invoiced';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_ON_HOLD = 'on_hold';

    protected $fillable = [
        'organization_id','customer_id','property_id','job_type_id','estimate_id','assigned_to','title','description','status',
        'scheduled_at','scheduled_end_at','started_at','arrived_at','completed_at','cancelled_at',
        'technician_notes','customer_notes','office_notes','access_instructions','rooms_count','bathrooms_count','square_feet','square_metres',
        'recurrence_frequency','recurrence_rule','requires_quality_check','quality_score',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime','scheduled_end_at' => 'datetime','started_at' => 'datetime','arrived_at' => 'datetime','completed_at' => 'datetime','cancelled_at' => 'datetime',
            'rooms_count' => 'integer','bathrooms_count' => 'decimal:1','square_feet' => 'integer','square_metres' => 'decimal:1','requires_quality_check' => 'boolean','quality_score' => 'integer',
        ];
    }

    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function property(): BelongsTo { return $this->belongsTo(Property::class); }
    public function jobType(): BelongsTo { return $this->belongsTo(JobType::class); }
    public function estimate(): BelongsTo { return $this->belongsTo(Estimate::class); }
    public function assignedTechnician(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
    public function lineItems(): HasMany { return $this->hasMany(JobLineItem::class)->orderBy('sort_order'); }
    public function checklistItems(): HasMany { return $this->hasMany(JobChecklistItem::class)->orderBy('sort_order'); }
    public function invoice(): HasOne { return $this->hasOne(Invoice::class); }
    public function attachments(): MorphMany { return $this->morphMany(Attachment::class, 'attachable'); }
    public function messages(): HasMany { return $this->hasMany(JobMessage::class)->orderByDesc('created_at'); }

    protected static function booted(): void
    {
        static::created(function (Job $job) {
            if (! $job->job_type_id) {
                return;
            }

            $templateItems = JobTypeChecklistItem::where('job_type_id', $job->job_type_id)->orderBy('sort_order')->get();

            foreach ($templateItems as $template) {
                $job->checklistItems()->create([
                    'organization_id' => $job->organization_id,
                    'job_type_checklist_item_id' => $template->id,
                    'label' => $template->label,
                    'instructions' => $template->instructions,
                    'category' => $template->taskLibraryItem?->category ?? 'general',
                    'estimated_minutes' => $template->taskLibraryItem?->estimated_minutes,
                    'sort_order' => $template->sort_order,
                    'is_required' => $template->is_required,
                    'requires_photo' => $template->requires_photo,
                ]);
            }
        });
    }

    public function isCompleted(): bool { return in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_INVOICED, self::STATUS_PAID], true); }
    public function isCancelled(): bool { return $this->status === self::STATUS_CANCELLED; }

    public static function statuses(): array
    {
        return [
            self::STATUS_SCHEDULED => 'Scheduled', self::STATUS_ASSIGNED => 'Assigned', self::STATUS_EN_ROUTE => 'En Route', self::STATUS_ARRIVED => 'Arrived', self::STATUS_IN_PROGRESS => 'In Progress', self::STATUS_QUALITY_CHECK => 'Quality Check', self::STATUS_COMPLETED => 'Completed', self::STATUS_INVOICED => 'Invoiced', self::STATUS_PAID => 'Paid', self::STATUS_CANCELLED => 'Cancelled', self::STATUS_ON_HOLD => 'On Hold',
        ];
    }

    public static function allowedTransitions(): array
    {
        return [
            self::STATUS_SCHEDULED    => [self::STATUS_ASSIGNED, self::STATUS_EN_ROUTE, self::STATUS_IN_PROGRESS, self::STATUS_ON_HOLD],
            self::STATUS_ASSIGNED     => [self::STATUS_SCHEDULED, self::STATUS_EN_ROUTE, self::STATUS_IN_PROGRESS, self::STATUS_ON_HOLD],
            self::STATUS_EN_ROUTE     => [self::STATUS_ARRIVED, self::STATUS_IN_PROGRESS, self::STATUS_ON_HOLD, self::STATUS_SCHEDULED],
            self::STATUS_ARRIVED      => [self::STATUS_IN_PROGRESS, self::STATUS_ON_HOLD, self::STATUS_EN_ROUTE],
            self::STATUS_IN_PROGRESS  => [self::STATUS_QUALITY_CHECK, self::STATUS_COMPLETED, self::STATUS_ON_HOLD],
            self::STATUS_QUALITY_CHECK => [self::STATUS_COMPLETED, self::STATUS_IN_PROGRESS],
            self::STATUS_COMPLETED    => [],
            self::STATUS_INVOICED     => [],
            self::STATUS_PAID         => [],
            self::STATUS_ON_HOLD      => [self::STATUS_SCHEDULED, self::STATUS_EN_ROUTE, self::STATUS_IN_PROGRESS],
            self::STATUS_CANCELLED    => [],
        ];
    }
}
