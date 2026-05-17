<?php

namespace App\Filament\Platform\Pages;

use Filament\Pages\Page;

class PanelLinks extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string|\UnitEnum|null $navigationGroup = 'Super Admin Access';

    protected static ?string $navigationLabel = 'All Panel Links';

    protected static ?string $title = 'Super Admin Links';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'filament-panels';

    protected string $view = 'filament.platform.pages.panel-links';

    public array $sections = [
        'Core Super Admin' => [
            ['label' => 'TitanPro Dashboard', 'description' => 'Primary super-admin Filament panel.', 'url' => '/titanpro', 'badge' => 'Core'],
            ['label' => 'Platform Dashboard', 'description' => 'Cross-tenant SaaS controls and organization oversight.', 'url' => '/platform/dashboard', 'badge' => 'Platform'],
            ['label' => 'Module Health & Repair', 'description' => 'Module registry, health checks, repair workflows, and enable/disable operations.', 'url' => '/admin/module-settings', 'badge' => 'Modules'],
            ['label' => 'Module Health Audit', 'description' => 'Professional audit trail for module lifecycle and repair actions.', 'url' => '/platform/modules/audit-log', 'badge' => 'Audit'],
        ],
        'Content / CMS / Theme' => [
            ['label' => 'CMS Pages', 'description' => 'Manage public CMS pages, marketing content, and generated pages.', 'url' => '/titanpro/cms-pages', 'badge' => 'CMS'],
            ['label' => 'Site Settings', 'description' => 'SEO, brand, public-site, and platform settings.', 'url' => '/titanpro/site-settings', 'badge' => 'Settings'],
            ['label' => 'Theme Manager', 'description' => 'Theme packages, visual theme controls, and theme publishing.', 'url' => '/titanpro/theme-manager', 'badge' => 'Theme'],
            ['label' => 'UI Studio', 'description' => 'Role UI profiles, tokens, layouts, widgets, and component overrides.', 'url' => '/titanpro/ui-studio', 'badge' => 'Studio'],
            ['label' => 'Public CMS Preview', 'description' => 'Front-end dynamic CMS pages route.', 'url' => '/pages/home', 'badge' => 'Public'],
        ],
        'TitanPro Resources' => [
            ['label' => 'Customers', 'description' => 'Customer records discovered under the main TitanPro resources.', 'url' => '/titanpro/customers', 'badge' => 'CRM'],
            ['label' => 'Properties', 'description' => 'Property records and customer property relationships.', 'url' => '/titanpro/properties', 'badge' => 'CRM'],
            ['label' => 'Attachments', 'description' => 'Uploaded media and customer/property attachment records.', 'url' => '/titanpro/attachments', 'badge' => 'Media'],
            ['label' => 'Jobs', 'description' => 'Jobs, schedules, job details, and operational work items.', 'url' => '/titanpro/jobs', 'badge' => 'Ops'],
            ['label' => 'Services', 'description' => 'Service/job-type configuration.', 'url' => '/titanpro/job-types', 'badge' => 'Ops'],
            ['label' => 'Service Checklists', 'description' => 'Checklist templates attached to service types.', 'url' => '/titanpro/job-type-checklist-items', 'badge' => 'Ops'],
            ['label' => 'Task Library', 'description' => 'Reusable checklist/task library.', 'url' => '/titanpro/job-checklist-items', 'badge' => 'Ops'],
            ['label' => 'Add-ons', 'description' => 'Items/add-ons used by quotes, jobs, and invoices.', 'url' => '/titanpro/items', 'badge' => 'Catalog'],
            ['label' => 'Quotes', 'description' => 'Estimate/quote records.', 'url' => '/titanpro/estimates', 'badge' => 'Quotes'],
            ['label' => 'Cleaning Packages', 'description' => 'Quote package templates and service bundles.', 'url' => '/titanpro/estimate-packages', 'badge' => 'Quotes'],
            ['label' => 'Invoices', 'description' => 'Invoice records, status, and payment visibility.', 'url' => '/titanpro/invoices', 'badge' => 'Finance'],
            ['label' => 'Payments', 'description' => 'Payment records and reconciliation visibility.', 'url' => '/titanpro/payments', 'badge' => 'Finance'],
            ['label' => 'Cleaner Locations', 'description' => 'Driver/cleaner location records and dispatch visibility.', 'url' => '/titanpro/driver-locations', 'badge' => 'Dispatch'],
            ['label' => 'Job Messages', 'description' => 'Job communications and message history.', 'url' => '/titanpro/job-messages', 'badge' => 'Comms'],
            ['label' => 'Message Templates', 'description' => 'Reusable customer/job message templates.', 'url' => '/titanpro/message-templates', 'badge' => 'Comms'],
            ['label' => 'Organization Settings', 'description' => 'Tenant/platform organization settings.', 'url' => '/titanpro/organization-settings', 'badge' => 'Platform'],
            ['label' => 'Reports', 'description' => 'TitanPro reporting page.', 'url' => '/titanpro/reports', 'badge' => 'Reporting'],
            ['label' => 'Operations Reports', 'description' => 'Operational reports page.', 'url' => '/titanpro/operations-reports', 'badge' => 'Reporting'],
        ],
        'Filament Panels' => [
            ['label' => 'GroundZero', 'description' => 'Owner/admin operations panel for jobs, dispatch, reports, team, customers.', 'url' => '/groundzero', 'badge' => 'Panel'],
            ['label' => 'TitanQuotes', 'description' => 'Quote pipeline and estimate workflow panel.', 'url' => '/titanquotes', 'badge' => 'Panel'],
            ['label' => 'ZeroPay', 'description' => 'Payments, subscriptions, invoices, and Stripe settings panel.', 'url' => '/zeropay', 'badge' => 'Panel'],
            ['label' => 'TitanGo', 'description' => 'Mobile/operator/PWA operations surface.', 'url' => '/titango', 'badge' => 'Panel'],
            ['label' => 'ZeroFuss', 'description' => 'Customer booking and self-service portal.', 'url' => '/zerofuss', 'badge' => 'Portal'],
            ['label' => 'TitanSolo', 'description' => 'Solo operator dashboard and simplified business panel.', 'url' => '/titansolo', 'badge' => 'Panel'],
            ['label' => 'TitanStudio', 'description' => 'Workflow, automation, CMS, templates, and studio tooling.', 'url' => '/titanstudio', 'badge' => 'Panel'],
            ['label' => 'TitanNexus', 'description' => 'Verticals, lead pipeline, marketing campaigns, training, and growth tools.', 'url' => '/titannexus', 'badge' => 'Panel'],
        ],
        'Module Consoles' => [
            ['label' => 'Accounting Dashboard', 'description' => 'Accounting, cashflow, journals, P&L, balance sheet, GST, ledger tools.', 'url' => '/account/dashboard', 'badge' => 'Accountings'],
            ['label' => 'Cashflow', 'description' => 'Cashflow forecast, runway, receivables, payables, collections.', 'url' => '/account/cashflow', 'badge' => 'Finance'],
            ['label' => 'Bookings', 'description' => 'Booking module list and dispatch board entry points.', 'url' => '/account/bookings', 'badge' => 'Booking'],
            ['label' => 'Booking Dispatch Board', 'description' => 'Dispatch board inside the Booking module.', 'url' => '/account/booking/dispatch-board', 'badge' => 'Dispatch'],
            ['label' => 'Assets', 'description' => 'Asset register, maintenance, allocations, revocations, scans, asset settings.', 'url' => '/assets', 'badge' => 'Asset'],
            ['label' => 'Biometric Devices', 'description' => 'Biometric devices, employees, attendance, commands, and device sync.', 'url' => '/biometric-devices', 'badge' => 'HR'],
            ['label' => 'Biometric Attendance', 'description' => 'Attendance feed and biometric attendance review.', 'url' => '/get-biometric-attendance', 'badge' => 'HR'],
            ['label' => 'Titan Zero', 'description' => 'AI assistant, generators, templates, document library, review queue, doctor.', 'url' => '/account/titan/zero', 'badge' => 'AI'],
            ['label' => 'Titan Zero Super Admin', 'description' => 'Titan Zero diagnostics, settings, logs, channels, personas, policy, tools, workflows.', 'url' => '/dashboard/super-admin/titan-zero', 'badge' => 'AI Admin'],
            ['label' => 'Titan Zero Doctor', 'description' => 'Document/library health doctor and support diagnostics.', 'url' => '/account/settings/titan-zero/doctor', 'badge' => 'Diagnostics'],
            ['label' => 'CRM Core', 'description' => 'CRMCore module entry. Exact route may depend on module provider registration.', 'url' => '/crm', 'badge' => 'CRM'],
        ],
        'Public / Product Pages' => [
            ['label' => 'Marketing Home', 'description' => 'Public marketing site home.', 'url' => '/', 'badge' => 'Public'],
            ['label' => 'Platform Product Page', 'description' => 'Public platform overview CMS page.', 'url' => '/platform', 'badge' => 'Public'],
            ['label' => 'Apps', 'description' => 'Public apps index.', 'url' => '/apps', 'badge' => 'Public'],
            ['label' => 'Service Modes', 'description' => 'Public service modes page.', 'url' => '/service-modes', 'badge' => 'Public'],
            ['label' => 'Industries', 'description' => 'Public industries page.', 'url' => '/industries', 'badge' => 'Public'],
            ['label' => 'Pricing', 'description' => 'Public pricing page.', 'url' => '/pricing', 'badge' => 'Public'],
            ['label' => 'Zero Philosophy', 'description' => 'Public Zero philosophy page.', 'url' => '/zero-philosophy', 'badge' => 'Public'],
            ['label' => 'Security', 'description' => 'Public security page.', 'url' => '/security', 'badge' => 'Public'],
            ['label' => 'AI Strategy', 'description' => 'Public AI strategy page.', 'url' => '/ai-strategy', 'badge' => 'Public'],
            ['label' => 'Automation Engine', 'description' => 'Public automation-engine page.', 'url' => '/automation-engine', 'badge' => 'Public'],
            ['label' => 'How It Works', 'description' => 'Public how-it-works page.', 'url' => '/how-it-works', 'badge' => 'Public'],
            ['label' => 'Features', 'description' => 'Public features page.', 'url' => '/features', 'badge' => 'Public'],
            ['label' => 'FAQ', 'description' => 'Public FAQ page.', 'url' => '/faq', 'badge' => 'Public'],
            ['label' => 'About', 'description' => 'Public about page.', 'url' => '/about', 'badge' => 'Public'],
            ['label' => 'Contact', 'description' => 'Public contact page.', 'url' => '/contact', 'badge' => 'Public'],
        ],
    ];

    /** @deprecated Kept so older views/customizations using $this->panels keep working. */
    public function getPanelsProperty(): array
    {
        return array_merge(...array_values($this->sections));
    }
}
