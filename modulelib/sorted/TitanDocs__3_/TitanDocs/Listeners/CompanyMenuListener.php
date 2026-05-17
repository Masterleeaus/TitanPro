<?php

namespace Modules\TitanDocs\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {        
        $module = 'AIDocument';
        $menu = $event->menu;
        if(!in_array('AIImage',$event->menu->modules))
        {
            $menu->add([
                'title' => 'AI',
                'icon' => 'brand-gitlab',
                'name' => 'ai',
                'parent' => null,
                'order' => 715,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => '',
                'module' => $module,
                'permission' => 'sidebar ai manage'
            ]);

            $menu->add([
                'title' => __('History'),
                'icon' => '',
                'name' => 'ai-history',
                'parent' => 'ai',
                'order' => 20,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => '',
                'module' => $module,
                'permission' => null
            ]);
        }
       
        $menu->add([
            'title' => __('Titan Docs'),
            'icon' => '',
            'name' => 'ai-document',
            'parent' => 'ai',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'titan-docs.index',
            'module' => $module,
            'permission' => 'ai document manage'
        ]);
        
        $menu->add([
            'title' => __('Titan Docs'),
            'icon' => '',
            'name' => 'history-ai-document',
            'parent' => 'ai-history',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'titan-docs.document.history',
            'module' => $module,
            'permission' => 'document history manage'
        ]);
        
    }
}
