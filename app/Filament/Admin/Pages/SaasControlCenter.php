<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Schema;

class SaasControlCenter extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform Commerce';

    protected static ?string $navigationLabel = 'SaaS Control Centre';

    protected static ?string $title = 'SaaS Control Centre';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'saas-control-centre';

    protected string $view = 'filament.admin.pages.saas-control-centre';

    public function getFeatureCardsProperty(): array
    {
        return [
            [
                'label' => 'Tenant Portfolio',
                'description' => 'Track businesses, organizations, workspaces, owners, and provisioning readiness.',
                'metric' => $this->firstAvailableCount(['businesses', 'organizations', 'tenants']),
                'status' => $this->firstExistingTable(['businesses', 'organizations', 'tenants']) ? 'Available' : 'Model mapping required',
                'intent' => 'Convert the legacy BusinessController workflow into Filament resources for tenant lifecycle management.',
            ],
            [
                'label' => 'Plans & Packages',
                'description' => 'Manage subscription packages, feature entitlements, popularity flags, and plan limits.',
                'metric' => $this->safeCount('saas_packages'),
                'status' => Schema::hasTable('saas_packages') ? 'Available' : 'Migration required',
                'intent' => 'Promote package management into Super Admin as a first-class plan catalogue.',
            ],
            [
                'label' => 'Subscriptions',
                'description' => 'Monitor tenant billing state, expiries, trial windows, and renewal risk.',
                'metric' => $this->safeCount('subscriptions'),
                'status' => Schema::hasTable('subscriptions') ? 'Available' : 'Migration required',
                'intent' => 'Add subscription health scoring and renewal alert automation.',
            ],
            [
                'label' => 'Coupons & Promotions',
                'description' => 'Govern discount codes, business-specific coupons, and campaign eligibility.',
                'metric' => $this->safeCount('saas_coupons'),
                'status' => Schema::hasTable('saas_coupons') ? 'Available' : 'Migration required',
                'intent' => 'Turn legacy coupon management into an auditable growth-control feature.',
            ],
            [
                'label' => 'Communications',
                'description' => 'Broadcast announcements, renewal alerts, onboarding messages, and operational notices.',
                'metric' => $this->safeCount('platform_communicator_logs'),
                'status' => Schema::hasTable('platform_communicator_logs') ? 'Available' : 'Migration required',
                'intent' => 'Add templated broadcast workflows with delivery logs and role targeting.',
            ],
            [
                'label' => 'Marketing Pages',
                'description' => 'Bring legacy frontend pages into the custom CMS and Tomato CMS publishing workflow.',
                'metric' => $this->safeCount('superadmin_frontend_pages'),
                'status' => Schema::hasTable('superadmin_frontend_pages') ? 'Available' : 'CMS import ready',
                'intent' => 'Migrate static marketing pages into the Site CMS with SEO governance.',
            ],
        ];
    }

    public function getUpgradePhasesProperty(): array
    {
        return [
            'Phase 1 — Stabilise' => [
                'Remove legacy controller coupling from Super Admin navigation.',
                'Expose only Filament-native governance features: tenants, plans, subscriptions, coupons, communications, CMS.',
                'Run missing migrations only after confirming table ownership and current production data.',
            ],
            'Phase 2 — Productise' => [
                'Create Super Admin resources for Plans, Subscriptions, Coupons, Broadcasts, and Tenant Portfolio.',
                'Add health scores for tenant billing, module status, queue status, storage, and failed jobs.',
                'Add repair actions: rebuild module cache, sync permissions, rebuild panel registry, publish assets, and rerun safe migrations.',
            ],
            'Phase 3 — Automate' => [
                'Introduce lifecycle automations for trial expiry, subscription renewal, inactive tenants, and failed payment recovery.',
                'Add a policy-aware broadcast centre with delivery receipts and audit log links.',
                'Add AI-assisted operator summaries for module drift, panel misconfiguration, and risky package changes.',
            ],
            'Phase 4 — Command Centre' => [
                'Create one Super Admin command palette for tenant impersonation, module repair, CMS publishing, plan assignment, and incident response.',
                'Add environment readiness checks for SQLite/MySQL, queues, cache, scheduler, storage links, Vite manifest, and Filament assets.',
                'Ship governance dashboards with trend cards for revenue, churn risk, module reliability, and content freshness.',
            ],
        ];
    }

    public function getRiskNotesProperty(): array
    {
        return [
            'Legacy SaaS concepts have been converted into Filament-native Super Admin resources rather than exposing outdated controller flows.',
            'New tables use explicit names to avoid collision with existing subscriptions, quote packages, and tenant data.',
            'Notifications and expiry alerts are good candidates for queue/scheduler integration after the data model is confirmed.',
        ];
    }

    private function firstAvailableCount(array $tables): string
    {
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                return $this->safeCount($table);
            }
        }

        return '0';
    }

    private function firstExistingTable(array $tables): ?string
    {
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }

    private function safeCount(string $table): string
    {
        try {
            return Schema::hasTable($table) ? (string) \DB::table($table)->count() : '0';
        } catch (\Throwable) {
            return '0';
        }
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasRole('super_admin');
    }
}
