<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class FieldJob extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'field_jobs';

    protected $fillable = [
        'company_id',
        'created_by',
        'updated_by',
        'reference',
        'type_id',
        'client_id',
        'technician_id',
        'asset_id',
        'parent_id',
        'status',
        'priority',
        'description',
        'notes',
        'scheduled_start',
        'scheduled_end',
        'due_at',
        'started_at',
        'completed_at',
        'preferred_date',
        'preferred_time',
        'preferred_note',
        'client_portal_token',
        'client_signed_at',
        'client_signature_path',
        'client_sign_name',
        'meta',
    ];

    protected $casts = [
        'scheduled_start'   => 'datetime',
        'scheduled_end'     => 'datetime',
        'due_at'            => 'datetime',
        'started_at'        => 'datetime',
        'completed_at'      => 'datetime',
        'client_signed_at'  => 'datetime',
        'meta'              => 'array',
    ];

    public const STATUS_PENDING   = 'pending';
    public const STATUS_APPROVED  = 'approved';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_ON_HOLD   = 'on_hold';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public static array $statuses = [
        self::STATUS_PENDING     => 'Pending',
        self::STATUS_APPROVED    => 'Approved',
        self::STATUS_SCHEDULED   => 'Scheduled',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_ON_HOLD     => 'On Hold',
        self::STATUS_COMPLETED   => 'Completed',
        self::STATUS_CANCELLED   => 'Cancelled',
    ];

    protected static function booted(): void
    {
        static::creating(function (FieldJob $job) {
            if (empty($job->client_portal_token)) {
                $job->client_portal_token = Str::random(40);
            }
            if (empty($job->reference)) {
                $job->reference = 'FJ-'.strtoupper(Str::random(8));
            }
        });
    }

    // --- Tenant scope ---
    public function scopeTenant(Builder $query, int $companyId): Builder
    {
        return $query->where('field_jobs.company_id', $companyId);
    }

    // --- Relationships ---
    public function type()
    {
        return $this->belongsTo(FieldJobType::class, 'type_id');
    }

    public function serviceParts()
    {
        return $this->hasMany(FieldJobServicePart::class, 'field_job_id');
    }

    public function serviceTasks()
    {
        return $this->hasMany(FieldJobServiceTask::class, 'field_job_id');
    }

    public function appointments()
    {
        return $this->hasMany(FieldJobAppointment::class, 'field_job_id');
    }

    public function comments()
    {
        return $this->hasMany(FieldJobComment::class, 'field_job_id');
    }

    public function checklistRuns()
    {
        return $this->hasMany(FieldJobChecklistRun::class, 'field_job_id');
    }

    public function inspections()
    {
        return $this->hasMany(FieldJobInspection::class, 'field_job_id');
    }

    public function permits()
    {
        return $this->hasMany(FieldJobPermit::class, 'field_job_id');
    }

    public function assets()
    {
        return $this->hasMany(FieldJobAsset::class, 'field_job_id');
    }

    public function partUsages()
    {
        return $this->hasMany(FieldJobPartUsage::class, 'field_job_id');
    }

    public function recurrences()
    {
        return $this->hasMany(FieldJobRecurrence::class, 'field_job_id');
    }

    public function parent()
    {
        return $this->belongsTo(FieldJob::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(FieldJob::class, 'parent_id');
    }

    // --- Helpers ---
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function totalAmount(): float
    {
        return (float) $this->serviceParts()->sum('amount');
    }
}
