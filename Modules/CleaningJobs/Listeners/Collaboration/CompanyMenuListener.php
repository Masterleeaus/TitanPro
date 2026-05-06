<?php

namespace Modules\CleaningJobs\Listeners\JobBoard;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'JobBoard';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Project Dashboard'),
            'icon' => '',
            'name' => 'jobboard-dashboards',
            'parent' => 'dashboard',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'jobboard.dashboard',
            'module' => $module,
            'permission' => 'jobboard dashboard manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Projects'),
            'icon' => 'square-check',
            'name' => 'projects',
            'parent' => null,
            'order' => 300,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'project manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Project'),
            'icon' => '',
            'name' => 'project',
            'parent' => 'projects',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'projects.index',
            'module' => $module,
            'permission' => 'project manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Project Report'),
            'icon' => '',
            'name' => 'project-report',
            'parent' => 'projects',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'project_report.index',
            'module' => $module,
            'permission' => 'project report manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('System Setup'),
            'icon' => '',
            'name' => 'system-setup',
            'parent' => 'projects',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'stages.index',
            'module' => $module,
            'permission' => 'jobboard setup manage'
        ]);
    }
}
