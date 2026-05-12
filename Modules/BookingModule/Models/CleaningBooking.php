<?php

namespace Modules\BookingModule\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * CleaningBooking — a Task specialised for the FSM cleaning workflow.
 *
 * All CleaningBooking records live in the core `tasks` table, scoped to
 * `task_type = 'booking'` via a global scope so they are never confused with
 * ordinary project tasks.
 *
 * @property int         $id
 * @property string      $task_type
 * @property string      $service_type
 * @property string|null $service_address
 * @property float|null  $service_lat
 * @property float|null  $service_lng
 * @property string|null $property_type
 * @property int|null    $bedrooms
 * @property int|null    $bathrooms
 * @property string|null $frequency
 * @property int|null    $recurrence_parent_id
 * @property string|null $access_method
 * @property string|null $alarm_code  (stored encrypted)
 * @property string|null $key_number
 * @property float|null  $estimated_duration_hours
 * @property float|null  $actual_duration_hours
 * @property bool        $supplies_required
 * @property int         $num_cleaners_required
 * @property string      $booking_status
 * @property \Illuminate\Support\Carbon|null $cleaner_arrived_at
 * @property \Illuminate\Support\Carbon|null $cleaner_departed_at
 * @property bool        $invoice_generated
 * @property int|null    $generated_invoice_id
 */
class CleaningBooking extends Task
{
    /**
     * Valid FSM booking statuses and their allowed next-states.
     *
     * 'reclean' is a re-opened completed booking (zero charge).
     */
    public const VALID_TRANSITIONS = [
        'pending'          => ['draft', 'confirmed', 'cancelled'],
        'draft'            => ['pending_approval', 'confirmed', 'cancelled', 'no_show'],
        'pending_approval' => ['confirmed', 'cancelled'],
        'confirmed'        => ['dispatched', 'cancelled', 'rescheduled', 'no_show'],
        'rescheduled'      => ['confirmed', 'cancelled'],
        'dispatched'       => ['in_progress', 'cancelled', 'rescheduled', 'no_show'],
        'in_progress'      => ['completed', 'cancelled', 'no_show'],
        'completed'        => ['invoiced', 'cancelled'],
        'invoiced'         => ['paid', 'cancelled'],
        'paid'             => [],
        'cancelled'        => [],
        'no_show'          => ['rescheduled', 'cancelled'],
    ];

    public const SERVICE_TYPES = [
        'regular', 'deep_clean', 'end_of_lease', 'carpet', 'window',
    ];

    public const PROPERTY_TYPES = [
        'residential', 'commercial', 'strata',
    ];

    public const FREQUENCIES = [
        'once', 'weekly', 'fortnightly', 'monthly',
    ];

    public const ACCESS_METHODS = [
        'client_present', 'key', 'lockbox', 'alarm',
    ];

    /**
     * Global scope that restricts queries to booking-type tasks only.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('booking', fn (Builder $q) => $q->where('task_type', 'booking'));

        // Ensure every new instance is stamped as a booking.
        static::creating(function (self $model) {
            $model->task_type = 'booking';
        });
    }

    /**
     * Extra attributes fillable beyond what Task allows.
     */
    protected $fillable = [
        'task_type',
        'booking_id',
        'service_type',
        'service_address',
        'service_lat',
        'service_lng',
        'property_type',
        'bedrooms',
        'bathrooms',
        'frequency',
        'recurrence_parent_id',
        'access_method',
        'alarm_code',
        'key_number',
        'estimated_duration_hours',
        'actual_duration_hours',
        'supplies_required',
        'num_cleaners_required',
        'booking_status',
        'cleaner_arrived_at',
        'cleaner_departed_at',
        'invoice_generated',
        'generated_invoice_id',
        'booking_value',
        'pending_approval_at',
        'approval_due_at',
        'approval_decision_at',
        'approval_decision_reason',
        'job_card_completed_at',
        'rescheduled_at',
        'no_show_at',
        'dispatched_at',
        'paid_at',
        // Inherit standard Task fillables as well.
        'heading',
        'description',
        'due_date',
        'start_date',
        'project_id',
        'task_category_id',
        'priority',
        'status',
        'board_column_id',
        'milestone_id',
        'created_by',
        'added_by',
        'is_private',
        'billable',
        'estimate_hours',
        'estimate_minutes',
    ];

    /**
     * Cast rules — alarm_code is stored encrypted at rest.
     */
    protected $casts = [
        'alarm_code'               => 'encrypted',
        'supplies_required'        => 'boolean',
        'invoice_generated'        => 'boolean',
        'cleaner_arrived_at'       => 'datetime',
        'cleaner_departed_at'      => 'datetime',
        'estimated_duration_hours' => 'float',
        'actual_duration_hours'    => 'float',
        'num_cleaners_required'    => 'integer',
        'bedrooms'                 => 'integer',
        'bathrooms'                => 'integer',
        'service_lat'              => 'float',
        'service_lng'              => 'float',
        'booking_value'            => 'float',
        'pending_approval_at'      => 'datetime',
        'approval_due_at'          => 'datetime',
        'approval_decision_at'     => 'datetime',
        'job_card_completed_at'    => 'datetime',
        'rescheduled_at'           => 'datetime',
        'no_show_at'               => 'datetime',
        'dispatched_at'            => 'datetime',
        'paid_at'                  => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * The parent recurring booking (if this is a child).
     */
    public function recurrenceParent(): BelongsTo
    {
        return $this->belongsTo(CleaningBooking::class, 'recurrence_parent_id')
                    ->withoutGlobalScope('booking');
    }

    /**
     * All child recurring bookings generated from this parent.
     */
    public function recurrenceChildren(): HasMany
    {
        return $this->hasMany(CleaningBooking::class, 'recurrence_parent_id')
                    ->withoutGlobalScope('booking');
    }

    // ─── FSM helpers ──────────────────────────────────────────────────────────

    /**
     * Return the list of statuses that can follow the current one.
     *
     * @return string[]
     */
    public function allowedNextStatuses(): array
    {
        return self::VALID_TRANSITIONS[$this->booking_status] ?? [];
    }

    /**
     * Check whether a transition to $nextStatus is permitted.
     */
    public function canTransitionTo(string $nextStatus): bool
    {
        return in_array($nextStatus, $this->allowedNextStatuses(), true);
    }

    /**
     * Return a Bootstrap badge colour class for a given status.
     */
    public static function statusBadge(string $status): string
    {
        return match ($status) {
            'pending'     => 'secondary',
            'draft'       => 'secondary',
            'pending_approval' => 'warning',
            'confirmed'   => 'info',
            'dispatched'  => 'primary',
            'in_progress' => 'warning',
            'completed'   => 'success',
            'invoiced'    => 'info',
            'paid'        => 'success',
            'cancelled'   => 'danger',
            'rescheduled' => 'primary',
            'no_show'     => 'dark',
            default       => 'light',
        };
    }
}
