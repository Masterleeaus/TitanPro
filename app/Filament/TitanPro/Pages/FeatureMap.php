<?php

namespace App\Filament\TitanPro\Pages;

use Filament\Pages\Page;

class FeatureMap extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-plus';

    protected static string|\UnitEnum|null $navigationGroup = 'Business Command';

    protected static ?string $navigationLabel = 'Feature Map';

    protected static ?string $title = 'Titan Pro Feature Map';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'feature-map';

    protected string $view = 'filament.titan-pro.pages.feature-map';

    public function getFeatureGroups(): array
    {
        return [
            'Customers & CRM' => [
                'intent' => 'Customer records, leads, deals, pipeline movement, scoring, and follow-up context.',
                'classes' => $this->availability([
                    'App\\Filament\\Resources\\CustomerResource',
                    'Modules\\CRMCore\\Filament\\Resources\\LeadResource',
                    'Modules\\CRMCore\\Filament\\Resources\\DealResource',
                    'Modules\\CRMCore\\Filament\\Resources\\ClientPipelineResource',
                ]),
            ],
            'Jobs & Dispatch' => [
                'intent' => 'Operational execution, field readiness, checklists, job messaging, and location visibility.',
                'classes' => $this->availability([
                    'App\\Filament\\Resources\\JobResource',
                    'App\\Filament\\Resources\\JobChecklistItemResource',
                    'App\\Filament\\Resources\\JobMessageResource',
                    'App\\Filament\\Resources\\DriverLocationResource',
                    'Modules\\TitanGoField\\Filament\\Pages\\MyJobsPage',
                    'Modules\\TitanGoField\\Filament\\Pages\\CheckInPage',
                ]),
            ],
            'Quotes & Revenue' => [
                'intent' => 'Estimate packages, line items, invoicing, payments, and owner-level revenue visibility.',
                'classes' => $this->availability([
                    'App\\Filament\\Resources\\EstimateResource',
                    'App\\Filament\\Resources\\EstimatePackageResource',
                    'App\\Filament\\Resources\\InvoiceResource',
                    'App\\Filament\\Resources\\PaymentResource',
                    'App\\Filament\\Resources\\ItemResource',
                ]),
            ],
            'Customer Experience' => [
                'intent' => 'Messages, attachments, properties, and business-facing content controls appropriate for Pro users.',
                'classes' => $this->availability([
                    'App\\Filament\\Resources\\MessageTemplateResource',
                    'App\\Filament\\Resources\\AttachmentResource',
                    'App\\Filament\\Resources\\PropertyResource',
                    'App\\Filament\\Resources\\CmsPageResource',
                ]),
            ],
        ];
    }

    public function getUpgradeBacklog(): array
    {
        return [
            'Create Pro-specific policies for owner, manager, dispatcher, technician, and accountant roles.',
            'Add workflow actions: create customer, create quote, schedule job, send invoice, request review.',
            'Bridge CRMCore deals into job creation so accepted opportunities become operational work.',
            'Add mobile readiness telemetry from TitanGoField to track field adoption and sync quality.',
            'Add dashboard trends for quote acceptance, unpaid invoice risk, job completion, and repeat customers.',
            'Keep Super Admin-only controls out of Titan Pro: users, roles, global themes, modules, platform repair, and package governance.',
        ];
    }

    private function availability(array $classes): array
    {
        return array_map(fn (string $class): array => [
            'class' => $class,
            'available' => class_exists($class),
        ], $classes);
    }
}
