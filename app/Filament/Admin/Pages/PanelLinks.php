<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class PanelLinks extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string|\UnitEnum|null $navigationGroup = 'Customisation';

    protected static ?string $navigationLabel = 'Panel Settings';

    protected static ?string $title = 'Panel Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'panel-settings';

    protected string $view = 'filament.admin.pages.panel-links';

    public function getPanels(): array
    {
        return collect(config('titan_panels.panels', []))
            ->map(fn (array $panel, string $id): array => [
                'id' => $id,
                'label' => $panel['label'] ?? str($id)->headline()->toString(),
                'description' => $panel['description'] ?? null,
                'url' => url('/' . trim((string) ($panel['path'] ?? $id), '/')),
                'badge' => in_array('super_admin', $panel['roles'] ?? [], true) ? 'Super Admin' : 'Panel',
            ])
            ->values()
            ->all();
    }

    public function getAdminTools(): array
    {
        return [
            ['label' => 'Dashboard', 'url' => url('/admin'), 'description' => 'Super Admin dashboard.'],
            ['label' => 'Users', 'url' => url('/admin/users'), 'description' => 'Super Admin user accounts, access review, and role assignment.'],
            ['label' => 'Roles & Permissions', 'url' => url('/admin/roles'), 'description' => 'Shield role and permission governance.'],
            ['label' => 'Activity Logs', 'url' => url('/admin/activity-logs'), 'description' => 'Platform audit trail and activity timeline.'],
            ['label' => 'Custom CMS Pages', 'url' => url('/admin/cms-pages'), 'description' => 'Manage the custom site CMS, page builder content, publishing status, SEO metadata, and live page previews.'],
            ['label' => 'Settings', 'url' => url('/admin/settings'), 'description' => 'Application, website, and platform configuration.'],
            ['label' => 'Theme Manager', 'url' => url('/admin/theme-manager'), 'description' => 'Appearance, theme package, and visual token management.'],
            ['label' => 'UI Manager', 'url' => url('/admin/ui-studio'), 'description' => 'Visual UI builder, role-based UI profiles, layouts, menu design, and component tokens.'],
            ['label' => 'Organization Settings', 'url' => url('/admin/organization-settings'), 'description' => 'Tenant and organization settings.'],
            ['label' => 'Super Admin Readiness', 'url' => url('/admin/super-admin-readiness'), 'description' => 'Readiness checklist and drift notes.'],
        ];
    }
}
