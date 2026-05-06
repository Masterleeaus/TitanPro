<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a single automation execution run.
 *
 * @property int         $id
 * @property int|null    $company_id
 * @property string      $automation_id
 * @property string      $trigger
 * @property string|null $handler
 * @property string      $status        queued|running|completed|failed
 * @property int         $attempts
 * @property int         $max_attempts
 * @property array|null  $payload
 * @property array|null  $output
 * @property string|null $exception
 * @property \Carbon\Carbon|null $started_at
 * @property \Carbon\Carbon|null $completed_at
 */
class AutomationRun extends Model
{
    protected $table = 'titan_automation_runs';

    protected $guarded = [];

    protected $casts = [
        'payload'      => 'array',
        'output'       => 'array',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Status constants
    public const STATUS_QUEUED    = 'queued';
    public const STATUS_RUNNING   = 'running';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED    = 'failed';

    public function markRunning(): void
    {
        $this->update([
            'status'     => self::STATUS_RUNNING,
            'started_at' => now(),
        ]);
    }

    public function markCompleted(mixed $output = null): void
    {
        $this->update([
            'status'       => self::STATUS_COMPLETED,
            'output'       => is_array($output) ? $output : ['result' => $output],
            'completed_at' => now(),
        ]);
    }

    public function markFailed(string $exception): void
    {
        $this->update([
            'status'       => self::STATUS_FAILED,
            'exception'    => $exception,
            'completed_at' => now(),
        ]);
    }

    public function hasExceededMaxAttempts(): bool
    {
        return $this->attempts >= $this->max_attempts;
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_QUEUED);
    }
}
