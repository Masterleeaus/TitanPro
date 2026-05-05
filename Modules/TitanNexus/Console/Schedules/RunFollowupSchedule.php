<?php
namespace Modules\TitanNexus\Console\Schedules;
final class RunFollowupSchedule { public function expression(): string { return '*/15 * * * *'; } }

