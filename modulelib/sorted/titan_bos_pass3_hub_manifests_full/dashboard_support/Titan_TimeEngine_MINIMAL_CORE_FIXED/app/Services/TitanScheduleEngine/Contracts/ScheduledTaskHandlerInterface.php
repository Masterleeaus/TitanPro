<?php

namespace App\Services\TitanScheduleEngine\Contracts;

use App\Models\Tz\TzScheduledTask;

interface ScheduledTaskHandlerInterface
{
    public function supports(TzScheduledTask $task): bool;

    public function handle(TzScheduledTask $task): array;
}
