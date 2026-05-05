<?php

namespace Modules\CleaningJobs\ControlPanel\Widgets;

use Illuminate\Support\Collection;

/**
 * OperationalWidgets hosts the widget definitions for the middle row of the control panel.
 *
 * Widgets should return collections or view models suitable for rendering
 * tabular or card-based summaries, for example lists of jobs, requests or inspections.
 */
class OperationalWidgets
{
    /**
     * Example widget: list of today's jobs with minimal information.
     */
    public function todaysJobs(): Collection
    {
        // TODO: fetch jobs from host system and transform into a simple collection
        return collect();
    }

    /**
     * Example widget: list of pending requests awaiting approval.
     */
    public function pendingRequests(): Collection
    {
        // TODO: fetch pending requests
        return collect();
    }
}