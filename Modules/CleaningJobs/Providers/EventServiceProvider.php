<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\CleaningJobs\Events\AppointmentCompleted;
use Modules\CleaningJobs\Events\AppointmentReminderSent;
use Modules\CleaningJobs\Events\AppointmentScheduled;
use Modules\CleaningJobs\Events\ClientJobRequested;
use Modules\CleaningJobs\Events\ConsumablesUsed;
use Modules\CleaningJobs\Events\InvoiceGenerated;
use Modules\CleaningJobs\Events\JobAssigned;
use Modules\CleaningJobs\Events\JobBudgetExceeded;
use Modules\CleaningJobs\Events\JobCancelled;
use Modules\CleaningJobs\Events\JobChecklistCompleted;
use Modules\CleaningJobs\Events\JobCompleted;
use Modules\CleaningJobs\Events\JobCreated;
use Modules\CleaningJobs\Events\JobExportedToCsv;
use Modules\CleaningJobs\Events\JobNoteAdded;
use Modules\CleaningJobs\Events\JobOnHold;
use Modules\CleaningJobs\Events\JobPriorityChanged;
use Modules\CleaningJobs\Events\JobReOpened;
use Modules\CleaningJobs\Events\JobRescheduled;
use Modules\CleaningJobs\Events\JobStageChanged;
use Modules\CleaningJobs\Events\JobStarted;
use Modules\CleaningJobs\Events\JobTaskAdded;
use Modules\CleaningJobs\Events\JobTaskCompleted;
use Modules\CleaningJobs\Events\RecurringJobTriggered;
use Modules\CleaningJobs\Events\TimesheetLogged;
use Modules\CleaningJobs\Events\WorkOrderCompleted;
use Modules\CleaningJobs\Events\WorkOrderCreated;
use Modules\CleaningJobs\Events\WorkOrderUpdated;
use Modules\CleaningJobs\Listeners\AutoConvertOnCompletion;
use Modules\CleaningJobs\Listeners\LogWorkOrderActivity;
use Modules\CleaningJobs\Listeners\SendWorkOrderWebhook;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WorkOrderCreated::class  => [SendWorkOrderWebhook::class, LogWorkOrderActivity::class],
        WorkOrderUpdated::class  => [SendWorkOrderWebhook::class, LogWorkOrderActivity::class],
        WorkOrderCompleted::class => [SendWorkOrderWebhook::class, AutoConvertOnCompletion::class, LogWorkOrderActivity::class],

        // Job Lifecycle
        JobCreated::class           => [],
        JobAssigned::class          => [],
        JobStarted::class           => [],
        JobCompleted::class         => [],
        JobCancelled::class         => [],
        JobRescheduled::class       => [],
        JobOnHold::class            => [],
        JobReOpened::class          => [],

        // Task/Checklist
        JobTaskAdded::class         => [],
        JobTaskCompleted::class     => [],
        JobChecklistCompleted::class => [],
        JobNoteAdded::class         => [],

        // Financial
        ConsumablesUsed::class      => [],
        InvoiceGenerated::class     => [],
        JobBudgetExceeded::class    => [],
        TimesheetLogged::class      => [],

        // Client/Appointment
        ClientJobRequested::class       => [],
        AppointmentScheduled::class     => [],
        AppointmentReminderSent::class  => [],
        AppointmentCompleted::class     => [],

        // System/Integration
        JobPriorityChanged::class   => [],
        JobStageChanged::class      => [],
        RecurringJobTriggered::class => [],
        JobExportedToCsv::class     => [],
    ];
}
