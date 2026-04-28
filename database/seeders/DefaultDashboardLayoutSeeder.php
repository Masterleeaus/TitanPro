<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Seeds a default "Welcome" dashboard layout using the registered Zeus widgets.
 *
 * The layout JSON is what the LaraZeus DynamicDashboard layout builder would
 * produce — each block has a "type" that matches the Block::make() key defined
 * in the Widget's form() method, and a "data" map of field values.
 *
 * Usage:
 *   php artisan db:seed --class=DefaultDashboardLayoutSeeder
 */
class DefaultDashboardLayoutSeeder extends Seeder
{
    public function run(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('layouts')) {
            $this->command->warn('layouts table does not exist — skipping DefaultDashboardLayoutSeeder.');
            return;
        }

        $slug = 'welcome-dashboard';

        // Idempotent: skip if already seeded
        if (DB::table('layouts')->where('layout_slug', $slug)->exists()) {
            $this->command->info('Default dashboard layout already exists — skipping.');
            return;
        }

        $widgets = [
            // Row 1: Welcome KPI header
            [
                'type' => 'kpi-grid-card',
                'data' => [
                    'title'   => 'Platform Overview',
                    'columns' => '4',
                    'kpis'    => json_encode([
                        ['label' => 'Total Jobs',    'value' => '—', 'icon' => 'heroicon-o-briefcase',        'color' => 'primary'],
                        ['label' => 'Revenue MTD',   'value' => '—', 'icon' => 'heroicon-o-currency-dollar',  'color' => 'success'],
                        ['label' => 'Active Techs',  'value' => '—', 'icon' => 'heroicon-o-users',            'color' => 'info'],
                        ['label' => 'Open Invoices', 'value' => '—', 'icon' => 'heroicon-o-document-text',    'color' => 'warning'],
                    ]),
                ],
            ],
            // Row 2: Welcome notice
            [
                'type' => 'alert-notice-card',
                'data' => [
                    'title'     => 'Welcome to TitanPro',
                    'message'   => 'This dashboard is fully customisable. Use Admin → Dashboard Layouts to add, remove or rearrange widgets.',
                    'type'      => 'info',
                    'show_icon' => true,
                ],
            ],
            // Row 3: Recent activity
            [
                'type' => 'recent-activity-card',
                'data' => [
                    'title'       => 'Recent Activity',
                    'max_items'   => 5,
                    'empty_state' => 'No recent activity yet.',
                    'items'       => json_encode([
                        ['label' => 'Dashboard created', 'time' => 'just now', 'color' => 'success'],
                    ]),
                ],
            ],
            // Row 3: CTA
            [
                'type' => 'cta-button-card',
                'data' => [
                    'heading'      => 'Create your first job',
                    'description'  => 'Get started by scheduling a job for one of your customers.',
                    'button_text'  => 'New Job →',
                    'button_url'   => '/admin/jobs/create',
                    'open_new_tab' => false,
                    'button_color' => 'primary',
                    'style'        => 'default',
                ],
            ],
            // Row 4: HTML card with setup tips
            [
                'type' => 'html-card',
                'data' => [
                    'title'   => 'Getting Started',
                    'content' => '<ol class="list-decimal list-inside space-y-1 text-sm text-gray-600 dark:text-gray-300">'
                        . '<li>Go to <strong>Admin → Dashboard Layouts</strong> to customise this dashboard.</li>'
                        . '<li>Add customers via <strong>Admin → Customers</strong>.</li>'
                        . '<li>Configure your job types in <strong>Admin → Job Types</strong>.</li>'
                        . '<li>Invite your team under <strong>Owner → Team</strong>.</li>'
                        . '<li>Set up your branding in <strong>Admin → Site Settings</strong>.</li>'
                        . '</ol>',
                ],
            ],
        ];

        DB::table('layouts')->insert([
            'user_id'      => 1,
            'layout_title' => 'Welcome Dashboard',
            'layout_slug'  => $slug,
            'widgets'      => json_encode($widgets),
            'is_active'    => 1,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $this->command->info('✓ Default dashboard layout seeded (slug: ' . $slug . ').');
    }
}
