<?php

namespace Modules\TitanGoField\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\TitanGoField\Models\FieldJob;
use Modules\TitanGoField\Models\FieldJobRecurrence;
use Modules\TitanGoField\Services\RecurrenceService;

class GenerateRecurringFieldJobsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly int $horizonDays = 30) {}

    public function handle(RecurrenceService $recurrenceService): void
    {
        $limit = now()->addDays($this->horizonDays);
        $count = 0;

        FieldJobRecurrence::where('is_active', true)
            ->whereNotNull('field_job_id')
            ->each(function (FieldJobRecurrence $rec) use ($limit, $recurrenceService, &$count) {
                $base = FieldJob::find($rec->field_job_id);
                if (!$base) {
                    return;
                }

                $next = $rec->next_run_at ? Carbon::parse($rec->next_run_at) : now();

                while ($next && $next <= $limit) {
                    $child = $base->replicate(['id', 'created_at', 'updated_at', 'client_portal_token', 'reference']);
                    $child->status       = FieldJob::STATUS_SCHEDULED;
                    $child->parent_id    = $base->id;
                    $child->scheduled_start = $next;
                    $child->save();

                    $rec->last_run_at = $next;
                    $next = $recurrenceService->nextOccurrence($rec->rrule, $next);
                    $rec->next_run_at = $next;
                    $rec->save();

                    $count++;
                }
            });

        Log::info("TitanGoField: Generated {$count} recurring field jobs.");
    }
}
