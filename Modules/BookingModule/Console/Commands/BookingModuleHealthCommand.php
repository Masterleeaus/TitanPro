<?php

namespace Modules\BookingModule\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class BookingModuleHealthCommand extends Command
{
    protected $signature = 'bookingmodule:health {--json : Output JSON}';
    protected $description = 'Run BookingModule blueprint and runtime health checks.';

    public function handle(): int
    {
        $checks = [
            'module_json' => file_exists(module_path('BookingModule', 'module.json')),
            'events_provider' => class_exists(\Modules\BookingModule\Providers\EventServiceProvider::class),
            'booking_table' => Schema::hasTable('bookings') || Schema::hasTable('tasks'),
            'schedules_table' => Schema::hasTable('schedules'),
            'appointment_settings_table' => Schema::hasTable('appointment_settings'),
            'reminder_logs_table' => Schema::hasTable('booking_reminder_logs'),
            'lifecycle_logs_table' => Schema::hasTable('booking_lifecycle_logs'),
            'ai_manifest' => file_exists(module_path('BookingModule', 'AI/Actions/action-map.json')),
            'agent_manifest' => file_exists(module_path('BookingModule', 'Agents/ModuleAgent/agent.manifest.json')),
            'navigation_manifest' => file_exists(module_path('BookingModule', 'manifests/navigation.json')),
        ];

        if ($this->option('json')) {
            $this->line(json_encode($checks, JSON_PRETTY_PRINT));
        } else {
            foreach ($checks as $name => $ok) {
                $this->line(($ok ? '<info>OK</info> ' : '<error>FAIL</error> ') . $name);
            }
        }

        return in_array(false, $checks, true) ? self::FAILURE : self::SUCCESS;
    }
}
