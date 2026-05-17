<?php

namespace App\Filament\TitanPro\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class CommandCenter extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-command-line';

    protected static string|\UnitEnum|null $navigationGroup = 'Business Command';

    protected static ?string $navigationLabel = 'Command Centre';

    protected static ?string $title = 'Titan Pro Command Centre';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.titan-pro.pages.command-center';

    public function getOperationalSnapshot(): array
    {
        return [
            'customers' => $this->countFirstAvailable(['customers', 'clients']),
            'jobs' => $this->countFirstAvailable(['jobs', 'bookings', 'work_orders']),
            'estimates' => $this->countFirstAvailable(['estimates', 'quotes']),
            'invoices' => $this->countFirstAvailable(['invoices']),
            'payments' => $this->countFirstAvailable(['payments']),
            'properties' => $this->countFirstAvailable(['properties']),
        ];
    }

    public function getFocusAreas(): array
    {
        return [
            [
                'title' => 'Revenue Command',
                'description' => 'Track quotes, invoices, payments, unpaid balances, and conversion quality from owner-level views.',
            ],
            [
                'title' => 'Operations Control',
                'description' => 'Monitor job load, dispatch readiness, checklists, messages, and field delivery signals.',
            ],
            [
                'title' => 'Customer Growth',
                'description' => 'Use CRM, deals, lead scoring, templates, and follow-up data to turn demand into booked work.',
            ],
            [
                'title' => 'Experience Layer',
                'description' => 'Keep business-facing CMS, customer communications, and mobile/PWA readiness aligned.',
            ],
        ];
    }

    public function getWorkflowLanes(): array
    {
        return [
            'Lead to Job' => [
                'Capture lead or customer request.',
                'Create estimate/package and approval path.',
                'Convert approved quote into scheduled job.',
            ],
            'Job to Cash' => [
                'Dispatch crew or technician with checklist context.',
                'Collect messages, notes, attachments, photos, and completion state.',
                'Generate invoice, record payment, and follow up overdue balances.',
            ],
            'Customer Care' => [
                'Centralise properties, attachments, templates, and job history.',
                'Surface upcoming jobs and customer communication gaps.',
                'Create repeat-service and review-request opportunities.',
            ],
        ];
    }

    public function getPanelReadiness(): array
    {
        return [
            ['label' => 'Titan Pro provider', 'state' => File::exists(app_path('Providers/Filament/TitanProPanelProvider.php')) ? 'Ready' : 'Missing'],
            ['label' => 'Titan Pro pages', 'state' => File::isDirectory(app_path('Filament/TitanPro/Pages')) ? 'Ready' : 'Missing'],
            ['label' => 'Titan Pro widgets', 'state' => File::isDirectory(app_path('Filament/TitanPro/Widgets')) ? 'Ready' : 'Missing'],
            ['label' => 'CRM Core module', 'state' => File::isDirectory(base_path('Modules/CRMCore')) ? 'Ready' : 'Not installed'],
            ['label' => 'Field Ops module', 'state' => File::isDirectory(base_path('Modules/TitanGoField')) ? 'Ready' : 'Not installed'],
        ];
    }

    public function refreshWorkspaceCache(): void
    {
        foreach (['cache:clear', 'view:clear', 'route:clear', 'config:clear'] as $command) {
            try {
                Artisan::call($command);
            } catch (\Throwable) {
                // Safe on restricted hosting where some commands may be unavailable.
            }
        }

        Notification::make()
            ->title('Titan Pro workspace refresh requested')
            ->body('Cache, route, view, and config clear commands were attempted safely.')
            ->success()
            ->send();
    }

    private function countFirstAvailable(array $tables): int|string
    {
        foreach ($tables as $table) {
            try {
                if (Schema::hasTable($table)) {
                    return DB::table($table)->count();
                }
            } catch (\Throwable) {
                return '—';
            }
        }

        return '0';
    }
}
