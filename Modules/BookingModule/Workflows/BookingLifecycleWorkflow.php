<?php

namespace Modules\BookingModule\Workflows;

class BookingLifecycleWorkflow
{
    public function stages(): array
    {
        return ['draft', 'pending_approval', 'confirmed', 'dispatched', 'in_progress', 'completed', 'invoiced', 'paid', 'cancelled', 'rescheduled', 'no_show'];
    }

    public function transitions(): array
    {
        return [
            'draft' => ['confirmed', 'pending_approval', 'cancelled', 'no_show'],
            'pending_approval' => ['confirmed', 'cancelled'],
            'confirmed' => ['dispatched', 'cancelled', 'rescheduled', 'no_show'],
            'dispatched' => ['in_progress', 'cancelled', 'rescheduled', 'no_show'],
            'in_progress' => ['completed', 'cancelled', 'no_show'],
            'completed' => ['invoiced', 'cancelled'],
            'invoiced' => ['paid', 'cancelled'],
            'paid' => [],
            'cancelled' => [],
            'rescheduled' => ['confirmed', 'cancelled'],
            'no_show' => ['rescheduled', 'cancelled'],
        ];
    }

    public function guardRules(): array
    {
        return [
            'confirmed->dispatched' => ['assigned_technicians_count >= 1'],
            'completed->invoiced' => ['received_signals includes JobCardCompleted'],
        ];
    }
}
